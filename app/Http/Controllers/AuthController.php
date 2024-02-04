<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255']
        ]);

        $user = User::where('username', $validated['username'])->first();

        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password'
            ]);
        } else if($user) {
            if(Hash::check($validated['password'], $user->password)) {
                $token = $user->createToken('bearer-token')->plainTextToken;

                return response()->json([
                    'status' => 'success',
                    'message' => 'Successful login',
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid username or password'
                ]);
            }
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
