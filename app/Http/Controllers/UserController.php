<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(Request $request)
{
    $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ];

    $this->validate($request,$rules);
 
    $user = new User;
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = $request->input('password');
    $user->api_token = Str::random(60);
    $user->save();

    return response()->json(['Message' => 'You are successfully registered ']);
}
public function login(Request $request)
{
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->input('email'))->first();

    if (!$user) {
        return response()->json(['Message' => 'Invalid Email or Password'], 401);
    }
    
    if ($request->input('password') !== $user->password) {
        return response()->json(['Message' => 'Invalid Email or Password'], 401);
    }

    return response()->json([
        'Message' => 'Login successful',
        'Access Token' => $user->api_token]);
}

public function profile(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['Message' => 'User not found'], 404);
    }
    $userWithoutApiToken = $user->makeHidden('api_token');
    return response()->json(['user' => $user]);
}

public function delete(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['Message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['Message' => 'User deleted successfully']);
    }
    public function update(Request $request)
{
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8',
    ]);

    $user = Auth::user();

    if (!$user) {
        return response()->json(['Message' => 'User not found'], 404);
    }

    $name = $request->input('name');
    $email = $request->input('email');
    $password = $request->input('password');

    if ($user->name === $name && $user->email === $email && $user->password === $password) {
        return response()->json(['Message' => 'No changes detected'], 400);
    }

    $user->name = $name;
    $user->email = $email;
    $user->password = $password;
    $user->save();

    return response()->json(['Message' => 'User updated successfully']);
}
}