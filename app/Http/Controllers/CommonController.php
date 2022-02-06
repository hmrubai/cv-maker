<?php

namespace App\Http\Controllers;

use Auth;
use App\User;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommonController extends Controller
{
    public function index(){
        return response()->json(['message' => 'Welcome to Makaw API!'], 200);
    }

    public function open() 
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);
    }

    public function get_user(Request $request)
    {
        $user = Auth::user();
        return response()->json(['success' => true, 'data' => $user, 'message' => 'User featched successful']);
    }

    public function userUpdate(Request $request)
    {
        $user_id = Auth::user()->id ? Auth::user()->id : 0;
        $user_info = User::where("id", $user_id)->first();

        if(empty($user_info)){
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => "User not found!"
            ], Response::HTTP_NOT_FOUND); 
        }

        if(!$request->name){
            return response()->json([
                'success' => false, 'data' => [], 'message' => "Please, enter name!"
            ], Response::HTTP_NOT_FOUND); 
        }

        if($request->name){
            User::where("id", $user_id)->update([
                "name" => $request->name,
            ]);
        }

        if($request->contact_no){
            User::where("id", $user_id)->update([
                "contact_no" => $request->contact_no
            ]);
        }

        if($request->address){
            User::where("id", $user_id)->update([
                "address" => $request->address
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => "User updated Successful"
        ], Response::HTTP_OK);
    }
}
