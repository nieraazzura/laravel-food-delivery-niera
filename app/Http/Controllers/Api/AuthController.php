<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //user register

    public function userRegister(Request $request)
    {
        $request->validate([
            'name' =>'required|string',
            'email' =>'required|email|unique:users',
            'password' => 'required|min:6|',
            'phone'=>'required|string',

        ]);

      $data =$request->all();
      $data['password']=hash::make($data['password']);
      $data['roles']='user';
      $user = User::create($data);

        return response()->json([
            'stastus'=> 'success',
           'message' => 'User created successfully',
           'data'=>$user

        ]);
    }

    
    // login
    public function login(Request $request)
    {

       $request->validate([
        'email' =>'required|email',
        'password' => 'required|min:6|',
       ]);
       $user = User::where('email',$request->email)->first();
       if(!$user || !hash::check($request->password,$user->password)){
        return response()->json([
           'status' => 'error',
           'message' => 'Invalid Credentials'
        ]);
       }

       $token = $user->createToken('auth_token')->plainTextToken;

       return response()->json([
           'status' =>'success',
           'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user
       ]
       );
    }

    //logout
    public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return response()->json([
       'status' =>'success',
       'message' => 'Logged out successfully'
    ]);
    }
    //restaurant register
    public function restaurantregister(Request $request){
    $request->validate([
        'name' =>'required',
        'email' =>'required|email|unique:users',
        'password' => 'required|min:6|',
        'phone'=>'required|string',
        'address'=>'required|string',
    ]);


    $data =$request->all();
    $data['password']=hash::make($data['password']);
    $data['roles']='restaurant';
    $user = User::create($data);

    return response()->json([
       'stastus'=> 'success',
       'message' => 'User created successfully',
        'data'=>$user
    ]);


    }
    //driver register

    public function driverregister(Request $request){
        {
            $request->validate([
                'name' =>'required',
                'email' =>'required|email|unique:users',
                'password' => 'required|min:6|',
                'phone'=>'required|string',
                'address'=>'required|string',
                'license_plate'=>'required|string',
                'photo'=>'required|string',

            ]);


            $data =$request->all();
            $data['password']=hash::make($data['password']);
            $data['roles']='driver';
            $user = User::create($data);

            return response()->json([
               'stastus'=> 'success',
               'message' => 'User created successfully',
                'data'=>$user
            ]);
        }

        //logout

}

//logout

}
