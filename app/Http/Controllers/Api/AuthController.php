<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\FollowUser;
use App\Models\FollowTag;
use App\Models\Tag;
use App\Models\Role;
use App\Models\User;
use App\Transformers\SingleUser\SingleUserTransformers;
use GuzzleHttp\Client;
use File;


class AuthController extends Controller
{
    private $singleUserTransformers;

    private $editUserTransformers;

    private $client;

    public function __construct(SingleUserTransformers $singleUserTransformers, Client $client)
    {
        $this->singleUserTransformers = $singleUserTransformers;
        $this->client = $client;
    }

    public function imageUpload(Request $request) {
        $rules = [
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
        ];
        $messages = [
            'image.required' => 'Image is required',
            'image.mimes' => 'Image invalid',
            'image.max' => 'Maximum image size to upload is 10000kb'
        ];
        $payload = [
            'image' => $request->image,
        ];
        $validator = Validator::make($payload, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Your request parameters did not validate',
                    'status' => 200,
                    'invalid_params' => $validator->errors(),
                    'detail' => 'Your request parameters did not validate',
                    'instance' => ''
                ]
            ], 200);
        }

        if($request->hasfile('image')) {
            $imageName = time().'.'.$request->file('image')->extension();
            $request->file('image')->move(public_path('images'), $imageName);

            return response()->json([
                'success' => true,
                'data' => $imageName
            ], 200);
        }
    }

    public function register(Request $request)
    {
        $rules = [
            'user_name' => 'unique:users',
            'email' => 'unique:users',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5000'
        ];
        $messages = [
            'user_name.unique' => 'User name already exists',
            'email.unique' => 'Email already exists',
            'avatar.image' => 'Avatar must be an image file',
            'avatar.mimes' => 'Avatar file must be .png .jpg .jpeg .gif',
            'avatar.max' => 'Maximum avatar size to upload is 5000kb'
        ];
        $payload = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'gender' => $request->gender,
            'avatar' => $request->avatar,
            'role_id' => Role::where('slug', 'user')->first()->id,
        ];
        $validator = Validator::make($payload, $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Your request parameters did not validate',
                    'status' => 200,
                    'invalid_params' => $validator->errors(),
                    'detail' => 'Your request parameters did not validate',
                    'instance' => ''
                ]
            ], 200);
        }

        if($request->hasfile('avatar')) {
            $avatarName = time().'.'.$request->file('avatar')->extension();
            $request->file('avatar')->move(public_path('images'), $avatarName);
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

        return response()->json([
            'success' => true,
            'data' =>  $user
        ], 200);
    }

    public function login(Request $request)
    {
        if ($request->provider == 'facebook') {
            return $this->checkFacebook($request->access_token);
        } else if ($request->provider == 'google') {
            return $this->checkGoogle($request->access_token);
        }
        $credentials = request(['user_name', 'password']);
        if(!auth()->attempt($credentials))
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => 'Incorrect username or password.',
                    'status' => 200,
                    'detail' => '',
                    'instance' => ''
                ]
            ], 200);
        $tokenResult = auth()->user()->createToken('Personal Access Token');
        return response()->json([
            'success' => true,
            'data' => [
                'id' => auth()->user()->id,
                'user_name' =>auth()->user()->user_name,
                'avatar' => auth()->user()->avatar,
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]
        ], 200);
    }

    public function checkFacebook($access_token)
    {
        try {
            $checkToken = $this->client->get("https://graph.facebook.com/v3.1/me?fields=id,first_name,last_name,email,picture&access_token=$access_token");
            $responseFacebook = json_decode($checkToken->getBody()->getContents(), true);
            return $this->checkUserFacebook($responseFacebook);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function checkGoogle($access_token)
    {
        try {
            $checkToken = $this->client->get("https://www.googleapis.com/oauth2/v3/userinfo?access_token=$access_token");
            $responseGoogle = json_decode($checkToken->getBody()->getContents(), true);
            return $this->checkUserGoogle($responseGoogle);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function checkUserFacebook($profile)
    {
        try {
            $user = User::where('facebook_id', $profile['id'])->first();
            if (!$user) {
                $avatarContent = file_get_contents($profile['picture']['data']['url']);
                $avatarName = time() . '.jpg';
                File::put(public_path() . '/images/' . $avatarName, $avatarContent);

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

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'user_name' => $user->user_name,
                    'avatar' => $user->avatar,
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => $e->getMessage(),
                    'status' => 500,
                    'detail' => $e->getMessage(),
                    'instance' => ''
                ]
            ], 500);
        }
    }

    public function checkUserGoogle($profile)
    {
        try{
            $user = User::where('google_id', $profile['sub'])->first();
            if (!$user) {

                $avatarContent = file_get_contents($profile['picture']);
                $avatarName = time() . '.jpg';
                File::put(public_path() . '/images/' . $avatarName, $avatarContent);

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

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'user_name' => $user->user_name,
                    'avatar' => $user->avatar,
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'type' => '',
                    'title' => $e->getMessage(),
                    'status' => 500,
                    'detail' => $e->getMessage(),
                    'instance' => ''
                ]
            ], 500);
        }
    }

    public function logoutUser(Request $request)
    {
        $user = auth()->user()->token();
        $user->revoke();
        return response()->json([
            'success' => true,
            'data' => [
                'user' => 'Logout user success'
            ]
        ]);
    }

    public function currentUser(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => auth()->user()->id,
                'user_name' =>auth()->user()->user_name,
                'avatar' => auth()->user()->avatar
            ]
        ]);
    }

    public function singleUser($user_name)
    {
        $user = User::where('user_name', $user_name);
        $singleUser = fractal($user->firstOrFail(), $this->singleUserTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleUser
        ], 200);
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
                'errors' => [
                    'type' => '',
                    'title' => 'User folllowed.',
                    'status' => 400,
                    'detail' => '',
                    'instance' => ''
                ]
            ], 400);
        }
    }

    public function unFollowUser(Request $request)
    {
        $user = auth()->user();

        $userFollowing = User::where('user_name', $request->user_name)->firstOrFail();

        $followCheck = FollowUser::where('user_id', $user->id)->where('following_id', $userFollowing->id)->firstOrFail();

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
                'errors' => [
                    'type' => '',
                    'title' => 'User unFolllowed.',
                    'status' => 400,
                    'detail' => '',
                    'instance' => ''
                ]
            ], 400);
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
