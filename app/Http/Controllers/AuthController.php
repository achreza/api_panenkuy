<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {        
        try {
            $user = new User($request->all());
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();            

            $response['user'] = $user;
            $token = $user->createToken('auth_token')->plainTextToken;

            $response['token'] = $token;

            $user->syncRoles('Customer');

            event(new Registered($user));

            

            return $this->successResponse($response, 'User has been Registered successfully, please check your email to verify it');
        } catch (QueryException $e) {

            return $this->errorResponse($e);
        }
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi ' . $user->fullname . ', welcome to home', 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
