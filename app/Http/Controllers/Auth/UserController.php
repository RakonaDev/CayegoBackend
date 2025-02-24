<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|string|min:6',
    ]);
    
    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }
    $data = $validator->validated();
    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
    ]);

    $token = JWTAuth::fromUser($user);

    return response()->json([
      'message' => 'User successfully registered',
      'user' => $user,
      'token' => $token
    ], 201);
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    if (!$token = JWTAuth::attempt($validator->validated())) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => JWTAuth::factory()->getTTL() * 60,
      'user' => Auth::user()
    ]);
  }

  public function user()
  {
    return response()->json(Auth::user());
  }

  public function logout()
  {
    JWTAuth::invalidate(JWTAuth::getToken());
    return response()->json(['message' => 'Successfully logged out']);
  }
}
