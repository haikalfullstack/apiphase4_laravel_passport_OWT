<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
   // registor - POST 
    public function register(Request $request){

      //validation
      $request->validate([
         "name" => "required",
         "email" =>  "required|email|unique:authors",
         "password" => "required|confirmed",
         "phone_no" => "required"
      ]);

      // create data
      $author = new Author();
      $author->name = $request->name;
      $author->email = $request->email;
      $author->password = bcrypt($request->password);
      $author->phone_no = $request->phone_no;


      // save data & send response
      $author->save();

      return response()->json([
         "status" => true,
         "message" => "Author created successfully",
      ]);

    }
   // login - POST
   public function login(Request $request){
      // vaidation
      $login_data = $request->validate([
         "email" => "required",
         "password" => "required"
      ]
        
      );

      // validate author data
      if(!auth()->attempt($login_data)){
         return response()->json([
            "status" => false,
            "message" => "Invalid Credentials"
         ]);
      }

      // token
      $token = auth()->user()->createToken("auth_token")->accessToken;

      // send response
      return response()->json([
         "status" => true,
         "message" => "Author Loggged in successfully",
         "accesss_token" => $token

      ]);


   }

   // profile - GET
   public function profile(){
      $user_data = auth()->user();

      return response()->json([
         "status" => true,
         "message" => "User data",
         "data" => $user_data
      ]);
   }

   // logout - POST
   public function logout(Request $request){

      // get token value
      // auth()->user()->token()
      $token = $request->user()->token();

      // revoke this token value
      $token->revoke();

      return response()->json([
         "status" => true,
         "message" => "Author logout successfully"
      ]);
   }
}
