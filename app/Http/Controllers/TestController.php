<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestController extends Controller
{
    public function login (Request $request)
    {
        $user = User::where('email', request('email'))->first();

        if($user){          
            if(Hash::check(request('password'), $user->password)){

                $token = $user->createToken('token-name')->plainTextToken;

                return response()->json([
                    'message' => 'success',
                    'data' => $user,
                    'token' => $token
                ], 200);

            }else{
                return response()->json([
                    'message' => 'Password tidak sesuai'
                ], 401);
            }
        }

        return response()->json([
            'message' => 'Tidak ada akun dengan email ' . request('email'),
        ], 401);
    }
}
