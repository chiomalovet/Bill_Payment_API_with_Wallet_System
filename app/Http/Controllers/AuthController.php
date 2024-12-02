<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        

        try{
            $validator = Validator::make($request->all(),[
               'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                
                  
            ]);
    
             if($validator->fails()){
              return response()->json([
                "status" => false,
                "messages" => "All Fields are mandatory",
                "error" => $validator->messages()
              ], 422);
             }
    
             $users= User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                
             ]);
             return response()->json([
                "status" => true,
                'message'=> 'User created successfully',
                // 'data' => new UserResource($users),
                "token" => $users->createToken("API TOKEN")->plainTextToken
             ], 201);

             // In User Registration logic or UserCreated Event
                $user->wallet()->create([
                'balance' => 0,
                ]);

    
            }catch(\Throwable $th){
                return response()->json([
                    "status" => false,
                    "message" => $th->getmessage()
                ], 400);
            }
        }


        public function login(Request $request)
    {
        try{
      $validator =validator::make($request->all(),
      [
            'email' => 'required',
            'password' => 'required',
      ]);

      if($validator->fails())
      {
        return response()->json([
        'status' => false,
        'message' => 'validation error',
        'errors' => $validator->errors()
        ], 401);
      }

      if(!Auth::attempt($request->only(['email', 'password']))){
           return response()->json([
            'status' => false,
             'message' => 'Email and Password does not match with our record',
           ], 401);
      }

        $user = User::where('email', $request->email)->first();

        return response()->json([
         'status' => true,
         'message' => "User Login Successfully",
         'token' => $user->createToken("API TOKEN")->plainTextToken,
        ], 200);
    }catch(\Throwable $th){
        return response()->json([
            "status" => false,
            "message" => $th->getmessage()
        ], 500);
    }
    }

}
