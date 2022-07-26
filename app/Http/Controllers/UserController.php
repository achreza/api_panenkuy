<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $this->authorize('show all user');

        $users = new UserCollection(User::paginate(15));

        return $this->successResponse($users,'Users list has been shown successfully');
    }

    public function show(User $user)
    {
        $this->authorize('show user');

        $user = new UserResource(User::findOrFail($user->id));

        return $this->successResponse($user,'Users list has been shown successfully');
    }

    public function store(UserStoreRequest $request)
    {

        $this->authorize('create user');

        try {
            $user = new User($request->all());

            $user->save();

            return $this->successResponse($user,'User has been created successfully');
        } catch (QueryException $e) {

            return $this->errorResponse($e);

        }
    }

    public function update(User $user, UserUpdateRequest $request)
    {

        $this->authorize('update user');
        try {

            $user->fill($request->all());

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();           

            return $this->successResponse($user,'User Updated Successfully');

        } catch (QueryException $e) {

            return $this->errorResponse($e);
        }
    }

    public function destroy(User $user)
    {

        $this->authorize('delete user');

        $user = User::findOrFail($user->id);

        try {
            $temp_user = $user;

            $user->delete();            

            return $this->successResponse($temp_user, 'User Deleted Successfully');
        } catch (QueryException $e) {
            return $this->errorResponse($e);
        }
    }

    public function beFarmer()
    {
        $user = auth()->user();
        
        if (!$user->email_verified_at) {
            return $this->errorResponse('Unauthorized', 'Please Veryfy ur email first', 403);
        }

        if ($user->hasRole('Farmer')) {
            return $this->successResponse(null,'Well, i guess you already a farmer');    
        }
        $user->assignRole('Farmer');

        return $this->successResponse(null,'Cool! You has become a farmer');
    }
}
