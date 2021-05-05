<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Follow;
use App\Models\User;


class FollowController extends Controller
{

    public function follow(Request $request)
    {
        $user = auth()->user();

        $userFollowing = User::where('user_name', $request->user_name)->first();

        $followCheck = Follow::where('user_id', $user->id)->where('following_id', $userFollowing->id)->first();

        if(!$followCheck) {
            $follow = new Follow;
            $follow->user_id = $user->id;
            $follow->following_id = $userFollowing->id;
            $follow->save();
            return response()->json([
                'success' => true,
                'data' =>  [
                    'id' => $follow->following->id,
                    'user_name' => $follow->following->user_name
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' =>  'folllowed'
            ]);
        }
    }

    public function unFollow(Request $request)
    {
        $user = auth()->user();

        $userFollowing = User::where('user_name', $request->user_name)->first();

        $followCheck = Follow::where('user_id', $user->id)->where('following_id', $userFollowing->id)->first();

        if(!!$followCheck) {
            $followCheck->delete();
            return response()->json([
                'success' => true,
                'data' =>  [
                    'id' => $followCheck->following->id,
                    'user_name' => $followCheck->following->user_name
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'errors' =>  'have not followed'
            ]);
        }
    }

}
