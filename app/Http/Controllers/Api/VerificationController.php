<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller {

    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'status' => 400,
                    'message' => 'Token error',
                    'code' => '666'
                ]
            ], 400);
        }

        $user = User::findOrFail($user_id);
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            return response()->json([
                'success' => true,
                'data' =>  [
                    'email' => $user->email
                ]
            ], 200);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'status' => 400,
                    'message' => 'Email verified',
                    'code' => '666'
                ]
            ], 400);
        }
    }

    public function resend(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'status' => 400,
                    'message' => 'Email not exists',
                    'code' => '666'
                ]
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'status' => 400,
                    'message' => 'Email verified',
                    'code' => '666'
                ]
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'data' =>  [
                'email' => $user->email
            ]
        ], 200);
    }
}
