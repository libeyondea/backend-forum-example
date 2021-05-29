<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\User;
use App\Transformers\EditProfile\EditProfileTransformers;

class SettingController extends Controller
{

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_name' => 'required|unique:users,user_name,'.$user->id,
            'email' => 'required|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10000'
        ];
        $messages = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'user_name.required' => 'User name is required',
            'email.required' => 'Email is required',
            'user_name.unique' => 'User name already exists',
            'email.unique' => 'Email already exists',
            'avatar.image' => 'Avatar must be an image file',
            'avatar.mimes' => 'Avatar file must be .png .jpg .jpeg .gif',
            'avatar.max' => 'Maximum avatar size to upload is 10000kb'
        ];
        $payload = [
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'user_name' => $request['user_name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number'],
            'address' => $request['address'],
            'gender' => $request['gender'],
            'avatar' => $request['avatar'],
        ];
        $validator = Validator::make($payload, $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'type' => '',
                    'title' => 'Your request parameters did not validate',
                    'status' => 200,
                    'invalid_params' => $validator->errors(),
                    'detail' => 'Your request parameters did not validate',
                    'instance' => ''
                ]
            ], 200);
        }

        $user = User::where('id', $user->id)->firstOrFail();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->user_name = $request['user_name'];
        $user->email = $request['email'];
        $user->phone_number = $request['phone_number'];
        $user->address = $request['address'];
        $user->gender = $request['gender'];

        if($request->hasfile('avatar')) {
            $oldAvatar = public_path('images/' . $user->avatar);
            if (File::exists($oldAvatar)) {
                File::delete($oldAvatar);
            }
            $avatarName = time() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->move(public_path('images'), $avatarName);
            $user->avatar = $avatarName;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'data' =>  $user
        ], 200);
    }

    public function editProfile()
    {
        $user = auth()->user();
        $editProfile = fractal($user, new EditProfileTransformers);
        return response()->json([
            'success' => true,
            'data' => $editProfile
        ], 200);
    }

    public function editCustomization()
    {
        return response()->json([
            'success' => true,
            'data' => ''
        ], 200);
    }
}
