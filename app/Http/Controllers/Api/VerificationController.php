<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends ApiController
{

    public function verify($user_id, Request $request) {

        if (!$request->hasValidSignature()) {
            return $this->respondNotFound();
        }

        $user = User::findOrFail($user_id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            return $this->respondSuccess([
                'email' => $user->email
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->respondNotFound();
        }
    }

    public function resend(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->respondUnprocessableEntity('Email not exists');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->respondUnprocessableEntity('Email verified');
        }

        $user->sendEmailVerificationNotification();

        return $this->respondSuccess([
            'email' => $user->email
        ]);
    }
}
