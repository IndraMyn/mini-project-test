<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $credentials = $request->only('email', 'password');
            if (!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json([
                    'message' => 'Email atau Password Anda salah!'
                ], 401);
            }

            return response()->json([
                'message' => "Successfully",
                'token' => $token
            ], 200);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }

    }

    public function register(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8'
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            //create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
    
            //return response JSON user is created
            if ($user) {
                return response()->json([
                    'success' => true,
                    'user' => $user,
                ], 201);
            }
    
            //return JSON process insert failed 
            return response()->json([
                'success' => false,
            ], 409);

        } catch (Exception $e) {

            return response(['error' => $e->getMessage()], 500);

        }
    }
}