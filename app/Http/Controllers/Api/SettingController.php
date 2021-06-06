<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Transformers\EditProfile\EditProfileTransformers;
use App\Http\Requests\Api\UpdateProfileRequest;

class SettingController extends ApiController
{
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = User::where('id', auth()->user()->id)->firstOrFail();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->user_name = $request['user_name'];
        $user->email = $request['email'];
        $user->phone_number = $request['phone_number'];
        $user->address = $request['address'];
        $user->gender = $request['gender'];

        if($request->hasfile('avatar')) {
            $oldAvatar = 'images/' . $user->avatar;
            if (Storage::disk('s3')->exists($oldAvatar)) {
                Storage::disk('s3')->delete($oldAvatar);
            }
            $avatarName = time() . '.' . $request->file('avatar')->extension();
            Storage::disk('s3')->put('images/' . $avatarName, file_get_contents($request->file('avatar')), 'public');

            $user->avatar = $avatarName;
        }

        if (User::where('id', auth()->user()->id)->firstOrFail()->email != $request['email']) {
            $user->email_verified_at = null;
            $user->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->save();
        }
        return $this->respondSuccess($user);
    }

    public function editProfile()
    {
        $user = auth()->user();
        $editProfile = fractal($user, new EditProfileTransformers);
        return $this->respondSuccess($editProfile);
    }

    public function editCustomization()
    {
        return $this->respondSuccess('');
    }
}
