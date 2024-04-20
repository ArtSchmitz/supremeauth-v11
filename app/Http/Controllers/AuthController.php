<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
  public function users()
  {
    return User::all();
  }

  public function register(Request $request)
  {
    $validateUser = Validator::make($request->all(),
    [
      'name' => 'required',
      'email' => 'required',
      'password' => 'required'
    ]);

    if ($validateUser->fails()) {
      return response()->json([
        'error' => true, 
        'message' => $validateUser->errors()
      ]);
    }

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    return response()->json([
      'error' => false,
      'message' => 'created',
      'token' => $user->createToken('TOKEN')->plainTextToken
    ], 200);
  }

  public function login(Request $request)
  {
    if (Auth::attempt($request->only('email', 'password'))){
      $user = Auth::user();
      return response()->json([
        'error' => false,
        'message' => 'logged',
        'token' => $user->createToken('TOKEN')->plainTextToken
    ]);
    } else {
      return response()->json(['error' => true, 'message' => 'Credenciais inválidas'], 401);
    }
  }
}