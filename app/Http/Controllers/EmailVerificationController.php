<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function verify(int $id, String $hash)
    {
        $user = User::findOrFail($id);

        abort_if(!$user, 403);

        abort_if(!hash_equals($hash, sha1($user->getEmailForVerification())), 403);


        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }

        return response()->json([
            'success' => true,
            'message' => 'user has been verified'
        ]);
    }

    public function resend(){
        if (auth('api')) {
            auth()->user()->sendEmailVerificationNotification();
            return response()->json([
                'message' => 'check your mailbox to verify your email'
            ]);
        }                
    }
}
