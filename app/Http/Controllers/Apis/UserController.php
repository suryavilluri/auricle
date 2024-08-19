<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Validator, Hash, JWTAuth, Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                // 'password' => 'required|min:8|confirmed',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(['user' => $user, 'token' => $token], 201);

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                $messages = $validator->messages();
                return response()->json(['status' => 400, 'message' => $messages->first()], 400);
            }

            $user = User::where('email', $request->email)->first();

            // if (Hash::check($request->password, $user->password)) {

            // } else {
            //     return response()->json(['status' => 401, 'message' => 'Invalid credentials'], 401);
            // }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $token = JWTAuth::fromUser($user);

                return response()->json(['status' => 200, 'message' => 'Login successful', 'user' => $user, 'token' => $token], 200);
            } else {
                return response()->json(['status' => 401, 'message' => 'Invalid credentials'], 401);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function user()
    {
        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['status' => 200, 'message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to logout, please try again.'], 500);
        }
    }

}
