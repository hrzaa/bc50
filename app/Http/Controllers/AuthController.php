<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'   => 'required',
            'password'   => 'required',
            'level' =>'required',

        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $post = User::create([
            'name'      => $request->name,
            'email'    => $request->email,
            'password'   => bcrypt($request->password),
            'level' => $request->level,

        ]);

        //success save to database
        if ($post) {

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data'    => $post
            ], 201);
        }

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Registrasi gagal',
        ], 409);
    }

    public function login()
    {
        dd(request()->all());
        try {
            if (!$token = JWTAuth::attempt([
                'email' => request()->email, 
                'password' => request()->password, 
                ])) {
                return response()->json([
                    'error' => 'username & password salah'
                ], 400);
            }
        } catch (JWTException $error) {
            return response()->json([
                'error' => 'kesalahan, tidak bisa membuat token'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token'    => $token,
            'user'    => JWTAuth::user(),
        ]);
    }
}
