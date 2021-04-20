<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Follow;
use App\Models\Role;
use App\Models\User;
use App\Transformers\UserTransformers;

class AuthController extends Controller
{
    private $user;

    private $userTransformers;

    public function __construct(User $user, UserTransformers $userTransformers)
    {
        $this->user = $user;
        $this->userTransformers = $userTransformers;
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
            'role_id' => Role::where('slug', 'guest')->first()->id,
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

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'data' => 'Logout success'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => auth()->user()
        ]);
    }

    public function listUser(Request $request, $limit = 20, $offset = 0)
    {
        $limit = $request->get('limit', $limit);
        $offset = $request->get('offset', $offset);
        $user = $this->user;
        $usersCount = $user->get()->count();
        $listUser = fractal($user->skip($offset)->take($limit)->get(), $this->userTransformers);
        return response()->json([
            'success' => true,
            'data' => $listUser,
            'meta' => [
                'users_count' => $usersCount
            ]
        ], 200);
    }

    public function singleUser($user_name)
    {
        $user = $this->user->where('user_name', $user_name);
        $singleUser = fractal($user->first(), $this->userTransformers);
        return response()->json([
            'success' => true,
            'data' => $singleUser,
        ], 200);
    }
}
