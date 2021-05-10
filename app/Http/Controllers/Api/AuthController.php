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
use App\Transformers\EditUser\EditUserTransformers;
use GuzzleHttp\Client;


class AuthController extends Controller
{
    private $singleUserTransformers;

    private $editUserTransformers;

    private $client;

    public function __construct(SingleUserTransformers $singleUserTransformers, EditUserTransformers $editUserTransformers, Client $client)
    {
        $this->singleUserTransformers = $singleUserTransformers;
        $this->editUserTransformers = $editUserTransformers;
        $this->client = $client;
    }

    public function register(Request $request)
    {
        $rules = [
            'user_name' => 'unique:users',
            'email' => 'unique:users'
        ];
        $messages = [
            'user_name.unique' => 'User name already exists',
            'email.unique' => 'Email already exists'
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
                'errors' => $validator->errors()
            ], 200);
        }
        $user = new User($payload);
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
                    "user" => "User name or password does not exists"
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
        $user = User::where('facebook_id', $profile['id'])->first();
        if (!$user) {
            $user = User::create([
                'facebook_id' => $profile['id'],
                'first_name' => $profile['first_name'],
                'last_name' => $profile['last_name'],
                'email' => $profile['email'],
                'user_name' => 'fb_'.$profile['id'],
                'password' => bcrypt(Str::random(9)),
                'avatar' => $profile['picture']['data']['url'],
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
    }

    public function checkUserGoogle($profile)
    {
        $user = User::where('google_id', $profile['sub'])->first();
        if (!$user) {
            $user = User::create([
                'google_id' => $profile['sub'],
                'first_name' => $profile['given_name'],
                'last_name' => $profile['family_name'],
                'email' => $profile['email'],
                'user_name' => 'gg_'.$profile['sub'],
                'password' => bcrypt(Str::random(9)),
                'avatar' => $profile['picture'],
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
    }

    public function logoutUser(Request $request)
    {
        $user = auth()->user()->token();
        $user->revoke();
        return response()->json([
            'success' => true,
            'data' => 'Logout success'
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

    public function updateUser(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'user_name' => 'required|unique:users,user_name,'.auth()->user()->id,
            'email' => 'required|unique:users,email,'.auth()->user()->id
        ];
        $messages = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'user_name.required' => 'User name is required',
            'email.required' => 'Email is required',
            'user_name.unique' => 'User name already exists',
            'email.unique' => 'Email already exists'
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
                'errors' => $validator->errors()
            ], 200);
        }
        $user = User::where('id', auth()->user()->id)->first();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->user_name = $request['user_name'];
        $user->email = $request['email'];
        $user->phone_number = $request['phone_number'];
        $user->address = $request['address'];
        $user->gender = $request['gender'];
        $user->avatar = $request['avatar'];
        $user->save();
        return response()->json([
            'success' => true,
            'data' =>  $user
        ], 200);
    }

    public function singleUser($user_name)
    {
        $user = User::where('user_name', $user_name);
        $singleUser = fractal($user->first(), $this->singleUserTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleUser
        ], 200);
    }

    public function editUser($user_name)
    {
        $user = auth()->user();
        if($user->user_name == $user_name) {
            $editUser = fractal($user, $this->editUserTransformers);
            return response()->json([
                'success' => true,
                'data' => $editUser
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'errors' => 'User does not exist'
            ], 404);
        }
    }

    public function followUser(Request $request)
    {
        $user = auth()->user();

        $userFollowing = User::where('user_name', $request->user_name)->first();

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
                'errors' =>  'folllowed'
            ]);
        }
    }

    public function unFollowUser(Request $request)
    {
        $user = auth()->user();

        $userFollowing = User::where('user_name', $request->user_name)->first();

        $followCheck = FollowUser::where('user_id', $user->id)->where('following_id', $userFollowing->id)->first();

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
