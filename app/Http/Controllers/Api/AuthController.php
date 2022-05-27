<?php
namespace App\Http\Controllers\Api;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['postLogin', 'postRegister']]);
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|string|min:2|max:100',
            'last_name'     => 'required|string|min:2|max:100',
            'email'         => 'required|string|email|max:100|unique:users',
            'password'      => 'required|string|same:confirm_password|min:6',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 200);
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        try {
            $check = User::where('email',$request->email)->first();
            if (!$check) {
                return response()->json([
                    'status' => 'error',
                    'errors' => 'User not found !'
                ], 200);
            }
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'status' => false,
                	'message' => 'Login credentials are invalid.',
                ], 422);
            }
        } catch (JWTException $e) {
            return response()->json([
                	'status' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        $format_response = $this->createNewToken($token,$check); // to format response
        return response()->json(['status' => 'successs','data'=>$format_response], 200)->header('Authorization', $token);
    }

    public function createNewToken($token,$check){
        $data = [];
        $data['access_token'] = $token;
        $data['token_type'] = 'bearer';
        $data['expires_in'] = date("Y-m-d H:i:s", strtotime("+30 day"));
        $data['user'] = $this->authInfo($check);
        return $data;
    }

    protected function authInfo($user)
    {
        return [
            'id'         => $user->id,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
        ];
    }
}
