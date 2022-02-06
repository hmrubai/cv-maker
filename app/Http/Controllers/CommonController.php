<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Auth;
use App\User;
use App\Models\AcademicInformation;

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
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => "User Profile not found!"
                ], Response::HTTP_NOT_FOUND);
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

    public function academicInformationsCreateUpdate(Request $request)
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

        if(!$request->exam_name){
            return response()->json([
                'success' => false, 'data' => [], 'message' => "Please, enter exam name!"
            ], Response::HTTP_NOT_FOUND); 
        }

        $payload = [
            "user_id"       => $user_id, 
            "exam_name"     => $request->exam_name, 
            "institute"     => $request->institute, 
            "cgpa"          => $request->cgpa, 
            "year"          => $request->year, 
            "is_completed"  => $request->is_completed, 
            "is_pursuing"   => $request->is_pursuing
        ];

        try {
            if($request->id){
                AcademicInformation::where('id', $request->id)->update($payload);
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => "Academic Information has been updated successfully"
                ], Response::HTTP_OK);

            } else {
                $isExist = AcademicInformation::where('user_id', $user_id)->where('exam_name', $request->exam_name)->first();
                if (empty($isExist)) {
                    AcademicInformation::create($payload);
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'message' => "Academic Information has been created successfully"
                    ], Response::HTTP_CREATED);
                }else{
                    return response()->json([
                        'success' => false,
                        'data' => [],
                        'message' => "Academic Information already Exist!"
                    ], Response::HTTP_ACCEPTED);
                }
            }

        } catch (Exception $e) {           
            return response()->json([
                'success' => false, 'data' => [], 'message' => "Please, Check details."
            ], Response::HTTP_NOT_FOUND);
        }
    }
}
