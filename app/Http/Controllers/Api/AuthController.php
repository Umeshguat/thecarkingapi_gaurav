<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
       try{
           
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $admin = Admin::where('email', $request->email)->first();

        
        if (Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('Web Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'token' => $token,
                'admin' => $admin,
            ]);
        }else{
            return response()->json([
                  'status' => 401,
                  'message' => 'Invalid credentials',
             ], 401);
        }
       } catch (\Exception $e) {

        return response()->json([
            'status' => 500,
            'message' => $e->getMessage(), // show real error
        ], 500);
    }
    }
}
