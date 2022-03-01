<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Auth,Validator,MyHelper,DB;
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

    public function getMyProfile(Request $request)
    {
        $myProfile = UserProfile::where(['user_id'=>Auth::user()->id])->first();

        if (empty($myProfile)){
            $myProfile=[];
        }else{
            $myProfile['profile_image']=url($myProfile->profile_image);
            $myProfile['signature']=url($myProfile->signature);
        }

        return response()->json(['success' => true, 'data' => $myProfile, 'message' => 'User fetch successful']);
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
                    'email'=>Auth::user()->email,
                    'contact_no'=>$request->contact_no,
                    'address'=>$request->address,
                ]);
            }else{
                $userProfile->update([
                    'name'=>$request->name,
                    'contact_no'=>$request->contact_no,
                    'address'=>$request->address,
                ]);
            }




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

        $data = $request->all();

        $validator = Validator::make($data, [
            'academic_data' => 'required|array|min:1',
            'academic_data.*.exam_name' => 'required|max:50',
            'academic_data.*.institute' => 'required|max:150',
            'academic_data.*.cgpa' => 'required|max:5|numeric',
            'academic_data.*.year' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $isExistAcademicInfo = AcademicInformation::where('user_id', $user_id)->first();
            if(!empty($isExistAcademicInfo)){
                AcademicInformation::where('user_id', $user_id)->delete();
            }


            foreach ($request->academic_data as $key=>$academic){
                $academicInput[]=[
                    "user_id"       => $user_id,
                    "exam_name"     => $academic['exam_name'],
                    "institute"     => $academic['institute'],
                    "cgpa"          => $academic['cgpa'],
                    "year"          => $academic['year'],
                    "is_completed"  => $academic['is_completed'],
                    "is_pursuing"   => $academic['is_pursuing'],
                    "created_at"    => date('Y-m-d h:i:s'),
                    "updated_at"    => date('Y-m-d h:i:s'),
                ];
            }

            AcademicInformation::insert($academicInput);
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Academic Information has been created successfully"
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 'data' => [], 'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function academicInformationsCreateUpdateOld(Request $request)
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

    public function academicInformationsList(Request $request)
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

        $all = AcademicInformation::where('user_id', $user_id)->get();
        return response()->json([
            'success' => true, 'data' => $all, 'message' => "Successful."
        ], Response::HTTP_OK);
    }

    public function userProfileImageUpdate(Request $request)
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

        $data = $request->all();

        $validator = Validator::make($data, [
            'profile_image' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $userProfile=UserProfile::where("user_id", $user_id)->first();

            if ($request->hasFile('profile_image')) {
                $profile_image=MyHelper::photoUpload($request->file('profile_image'),'uploads/profile-image');

                if (!empty($userProfile) && file_exists($userProfile->profile_image)){
                    unlink($userProfile->profile_image);
                }

                $userProfile->update(['profile_image'=>$profile_image]);
            }

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Profile Image Upload Successful"
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {

            return response()->json([
                'success' => false, 'data' => [], 'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function userSignatureUpdate(Request $request)
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

        $data = $request->all();

        $validator = Validator::make($data, [
            'signature' => 'required|mimes:jpeg,jpg,png,gif|max:50000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $userProfile=UserProfile::where("user_id", $user_id)->first();

            if ($request->hasFile('signature')) {
                $signature=MyHelper::photoUpload($request->file('signature'),'uploads/user-signature');

                if (!empty($userProfile) && file_exists($userProfile->signature)){
                    unlink($userProfile->signature);
                }

                $userProfile->update(['signature'=>$signature]);
            }

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Signature Image Upload Successful"
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {

            return response()->json([
                'success' => false, 'data' => [], 'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }


    public function academicInformationDestroy($academicId){
        $user_id = Auth::user()->id ? Auth::user()->id : 0;
        $user_info = User::where("id", $user_id)->first();

        if(empty($user_info)){
            return response()->json([
                'success' => false,'data' => [],'message' => "User not found!"
            ], Response::HTTP_NOT_FOUND);
        }

        try{
            $userAcademicData=AcademicInformation::where(['user_id'=>$user_id,'id'=>$academicId])->first();

            if (empty($userAcademicData))
            {
                return response()->json([
                    'success' => false,'data' => [],'message' => "Academic Data not found!"
                ], Response::HTTP_NOT_FOUND);
            }

            $userAcademicData->delete();

            return response()->json([
                'success' => true,'data' => [],'message' => "Your Academic Data has been Removed successfully!"
            ], Response::HTTP_NOT_FOUND);

        }catch (\Exception $e)
        {
            return response()->json([
                'success' => false, 'data' => [],'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
