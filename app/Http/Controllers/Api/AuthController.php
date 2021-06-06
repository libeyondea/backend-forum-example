<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Exception;
use File;
use App\Models\FollowUser;
use App\Models\FollowTag;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Mail\VerifyMail;
use App\Transformers\SingleUser\SingleUserTransformers;
use App\Http\Requests\Api\RegisterRequest;

class AuthController extends ApiController
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function register(RegisterRequest $request)
    {
        if($request->hasfile('avatar')) {

            $avatarName = time() . '.' . $request->file('avatar')->extension();
            Storage::disk('s3')->put('images/' . $avatarName, file_get_contents($request->file('avatar')), 'public');

        } else {
            $avatarName = 'default_avatar.png';
        }

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->gender = $request->gender;
        $user->avatar = $avatarName;
        $user->role_id = Role::where('slug', 'user')->first()->id;
        $user->save();
        $user->sendEmailVerificationNotification();

        return $this->respondSuccess($user);
    }

    public function login(Request $request)
    {
        if ($request->provider == 'facebook') {
            return $this->checkFacebook($request->access_token);
        } else if ($request->provider == 'google') {
            return $this->checkGoogle($request->access_token);
        }

        $credentials = request(['user_name', 'password']);

        if(!auth()->attempt($credentials)) {
            return $this->respondUnprocessableEntity('Incorrect username or password');
        }

        $tokenResult = auth()->user()->createToken('Personal Access Token');

        return $this->respondSuccess([
            'id' => auth()->user()->id,
            'user_name' =>auth()->user()->user_name,
            'avatar' => auth()->user()->avatar,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function checkFacebook($access_token)
    {
        try {
            $checkToken = $this->client->get("https://graph.facebook.com/v3.1/me?fields=id,first_name,last_name,email,picture&access_token=$access_token");
            $responseFacebook = json_decode($checkToken->getBody()->getContents(), true);
            return $this->checkUserFacebook($responseFacebook);
        } catch (Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function checkGoogle($access_token)
    {
        try {
            $checkToken = $this->client->get("https://www.googleapis.com/oauth2/v3/userinfo?access_token=$access_token");
            $responseGoogle = json_decode($checkToken->getBody()->getContents(), true);
            return $this->checkUserGoogle($responseGoogle);
        } catch (Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function checkUserFacebook($profile)
    {
        try {
            $user = User::where('facebook_id', $profile['id'])->first();
            if (!$user) {

                $avatarContent = $profile['picture']['data']['url'];
                $avatarName = time() . '.jpg';
                Storage::disk('s3')->put('images', file_get_contents($avatarContent), 'public');

                $user = User::create([
                    'facebook_id' => $profile['id'],
                    'first_name' => $profile['first_name'],
                    'last_name' => $profile['last_name'],
                    'email' => $profile['email'],
                    'user_name' => 'fb_'.$profile['id'],
                    'password' => bcrypt(Str::random(9)),
                    'avatar' => $avatarName,
                    'role_id' => Role::where('slug', 'user')->first()->id,
                ]);
            }

            $tokenResult = $user->createToken('Personal Access Client');

            return $this->respondSuccess([
                'id' => $user->id,
                'user_name' => $user->user_name,
                'avatar' => $user->avatar,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function checkUserGoogle($profile)
    {
        try{
            $user = User::where('google_id', $profile['sub'])->first();
            if (!$user) {

                $avatarContent = $profile['picture'];
                $avatarName = time() . '.jpg';
                Storage::disk('s3')->put('images/' . $avatarName, file_get_contents($avatarContent), 'public');

                $user = User::create([
                    'google_id' => $profile['sub'],
                    'first_name' => $profile['given_name'],
                    'last_name' => $profile['family_name'],
                    'email' => $profile['email'],
                    'user_name' => 'gg_'.$profile['sub'],
                    'password' => bcrypt(Str::random(9)),
                    'avatar' => $avatarName,
                    'role_id' => Role::where('slug', 'user')->first()->id,
                ]);
            }

            $tokenResult = $user->createToken('Personal Access Client');

            return $this->respondSuccess([
                'id' => $user->id,
                'user_name' => $user->user_name,
                'avatar' => $user->avatar,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    public function logoutUser(Request $request)
    {
        $user = auth()->user()->token();
        $user->revoke();
        return $this->respondSuccess([
            'user' => 'Logout success'
        ]);
    }

    public function currentUser(Request $request)
    {
        return $this->respondSuccess([
            'id' => auth()->user()->id,
            'user_name' =>auth()->user()->user_name,
            'avatar' => auth()->user()->avatar
        ]);
    }

    public function singleUser($user_name)
    {
        $user = User::where('user_name', $user_name);
        $singleUser = fractal($user->firstOrFail(), new SingleUserTransformers);
        return $this->respondSuccess($singleUser);
    }

    public function followUser(Request $request)
    {
        $user = auth()->user();
        $userFollowing = User::where('user_name', $request->user_name)->firstOrFail();
        $followCheck = FollowUser::where('user_id', $user->id)->where('following_id', $userFollowing->id)->first();

        if(!$followCheck) {
            $follow = new FollowUser;
            $follow->user_id = $user->id;
            $follow->following_id = $userFollowing->id;
            $follow->save();
            return $this->respondSuccess([
                'id' => $follow->following->id,
                'user_name' => $follow->following->user_name
            ]);
        } else {
            return $this->respondUnprocessableEntity('User folllowed');
        }
    }

    public function unFollowUser(Request $request)
    {
        $user = auth()->user();
        $userFollowing = User::where('user_name', $request->user_name)->firstOrFail();
        $followCheck = FollowUser::where('user_id', $user->id)->where('following_id', $userFollowing->id)->first();

        if(!!$followCheck) {
            $followCheck->delete();
            return $this->respondSuccess([
                'id' => $followCheck->following->id,
                'user_name' => $followCheck->following->user_name
            ]);
        } else {
            return $this->respondUnprocessableEntity('User does not exist or not in the followlist');
        }
    }

    // FB User Deletion
    public function facebookUserDeletion(Request $request)
    {
        $data = $this->parseFacebookSignedRequest($request->signed_request);
        $user_id = $data['user_id'];

        $deleteUser = User::where('facebook_id', $user_id)->firstOrFail();
        $deleteUser->delete();

        $status_url = 'https://www.de4thzone.com/deletion?id=' . $deleteUser->id; // URL to track the deletion
        $confirmation_code = $deleteUser->id; // unique code for the deletion request

        return response()->json([
            'url' => $status_url,
            'confirmation_code' => $confirmation_code
        ], 200);
    }

    public function parseFacebookSignedRequest($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = "39a2d3ae0e11b9667a457bc19416cc3d"; // Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
