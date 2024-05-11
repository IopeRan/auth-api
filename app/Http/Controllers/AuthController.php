<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::firstWhere("username", $request->username);

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("auth_token")->plainTextToken;

                return response()->json([
                    "Body" => [
                        "message" => "Login success",
                        "token" => $token,
                        "user" => $user
                    ]
                ], 200);
            } else {
                return response()->json([
                    "message" => "Wrong password"
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Wrong Username'
            ]);
        }
    }

    public function registration(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json($validated->errors(), Response::HTTP_BAD_REQUEST);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $message = [
            'message' => 'User Registered Successfully',
            'data' => $user
        ];
        $response = Response::HTTP_OK;

        return response()->json($message, $response);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $request->user()->tokens()->delete();

        return response()->json([
            "Body" => [
                "message" => "Logout Successfully"
            ]
        ], Response::HTTP_OK);
    }
}
