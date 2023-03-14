<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Hash;
use App\Http\Requests\user\StoreRequest;
use App\Http\Requests\user\UpdateRequest;
class AuthController extends Controller
{

    public function register(StoreRequest $request){

        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->image=$request->image;
        $user->gender=$request->gender;
        $user->save();
        return $user; 
    }
    public function login(Request $request){


        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(Auth::attempt($credentials)){
            $user=Auth::user();
           $token= $user->createToken('token-name', ['server:update'])->plainTextToken;
            // $token = $user->createToken($request->token_name);
            return response()->json([$user,$token]);
        }
        else{
            return 'un authorized';
        }
        
        
    }

    public function update(UpdateRequest $request,$id){

        $user=User::findOrFail($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->image=$request->image;
        $user->gender=$request->gender;
        $user->save();
        return $user; 
    }

    public function delete($id){

        $user=User::findOrFail($id);
        $user->delete();
        return $user; 
    }


    public function upload(Request $request){
        $image_path = $request->file('file')->store('images', 'public');
       
        return $image_path; 
    }
}
