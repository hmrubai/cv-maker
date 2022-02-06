<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
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

        try{
            $authUserId=Auth::user()->id;

            $userProfile=UserProfile::where(['user_id'=>$authUserId])->first();

            if(empty($userProfile)){
                UserProfile::create([
                    'user_id'=>$authUserId,
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'contact_no'=>$request->contact_no,
                    'address'=>$request->address,
                ]);
            }


            $userProfile->update([
                'name'=>$request->name,
                'contact_no'=>$request->contact_no,
                'address'=>$request->address,
            ]);

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Your Profile Update successful."
            ], Response::HTTP_OK);

        }catch (\Exception $e){

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
