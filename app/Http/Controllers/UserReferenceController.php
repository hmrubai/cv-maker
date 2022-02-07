<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserReference;
use Auth,Validator,DB;
use App\User;

use Symfony\Component\HttpFoundation\Response;

class UserReferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        $myReferenceData = UserReference::where('user_id', $user_id)->get();
        return response()->json([
            'success' => true, 'data' => $myReferenceData, 'message' => "My reference data list"
        ], Response::HTTP_NOT_FOUND);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'reference_data' => 'required|array|min:1',
            'reference_data.*.name' => 'required|max:150',
            'reference_data.*.designation' => 'required|max:150',
            'reference_data.*.organization' => 'required|max:200',
            'reference_data.*.email' => 'required|email|max:100',
            'reference_data.*.mobile' => 'nullable|max:30',
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

            $isExistProjectInfo = UserReference::where('user_id', $user_id)->first();
            if(!empty($isExistProjectInfo)){
                UserReference::where('user_id', $user_id)->delete();
            }


            foreach ($request->reference_data as $key=>$referenceData){
                $referenceDataInput[]=[
                    "user_id"       => $user_id,
                    "name"     => $referenceData['name'],
                    "designation"     => $referenceData['designation'],
                    "organization"          => $referenceData['organization'],
                    "email"          => $referenceData['email'],
                    "mobile"          => $referenceData['mobile'],
                    "created_at"          => date('Y-m-d h:i:s'),
                    "updated_at"          => date('Y-m-d h:i:s'),
                ];
            }

            UserReference::insert($referenceDataInput);
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Reference Information has been created successfully"
            ], Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 'data' => [], 'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserReference  $userReference
     * @return \Illuminate\Http\Response
     */
    public function show(UserReference $userReference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserReference  $userReference
     * @return \Illuminate\Http\Response
     */
    public function edit(UserReference $userReference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserReference  $userReference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserReference $userReference)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserReference  $userReference
     * @return \Illuminate\Http\Response
     */
    public function destroy($referenceId)
    {
        $user_id = Auth::user()->id ? Auth::user()->id : 0;
        $user_info = User::where("id", $user_id)->first();

        if(empty($user_info)){
            return response()->json([
                'success' => false,'data' => [],'message' => "User not found!"
            ], Response::HTTP_NOT_FOUND);
        }

        try{
            $userReference=UserReference::where(['user_id'=>$user_id,'id'=>$referenceId])->first();

            if (empty($userReference))
            {
                return response()->json([
                    'success' => false,'data' => [],'message' => "Reference not found!"
                ], Response::HTTP_NOT_FOUND);
            }

            $userReference->delete();

            return response()->json([
                'success' => true,'data' => [],'message' => "Your Reference has been Removed successfully!"
            ], Response::HTTP_NOT_FOUND);

        }catch (\Exception $e)
        {
            return response()->json([
                'success' => false, 'data' => [],'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
