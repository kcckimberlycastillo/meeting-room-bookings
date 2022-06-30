<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

use Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @return [string] message
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->all()], 400);
        } 

        $user = new User([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if (!$user->save()) {
            return response()->json(['message' => 'Something went wrong. Please contact the administrator.'], 500);
        }

        return response()->json([
            'message' => 'Successfully registered!'
        ], 201);

    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->all()], 400);
        } 
        
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])){
            $user = $request->user();

            return response()->json(['userData' => $user], 200);
        } 

        return response()->json(['message' => 'Unauthorized'], 400);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
