<?php

namespace App\Http\Controllers;

use App\Models\UserProject;
use Illuminate\Http\Request;
use Auth,Validator,DB;
use App\User;

use Symfony\Component\HttpFoundation\Response;


class UserProjectController extends Controller
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

        $myProjectData = UserProject::where('user_id', $user_id)->get();
        return response()->json([
            'success' => true, 'data' => $myProjectData, 'message' => "Project data list"
        ], Response::HTTP_OK);
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
            'project_data' => 'required|array|min:1',
            'project_data.*.project_name' => 'required|max:200',
            'project_data.*.description' => 'required|max:1000',
            'project_data.*.responsibility' => 'required|max:1000',
            'project_data.*.project_link' => 'nullable|max:255',
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

            $isExistProjectInfo = UserProject::where('user_id', $user_id)->first();
            if(!empty($isExistProjectInfo)){
                UserProject::where('user_id', $user_id)->delete();
            }


            foreach ($request->project_data as $key=>$projectData){
                $projectDataInput[]=[
                    "user_id"       => $user_id,
                    "project_name"     => $projectData['project_name'],
                    "description"     => $projectData['description'],
                    "responsibility"          => $projectData['responsibility'],
                    "project_link"          => $projectData['project_link'],
                    "created_at"          => date('Y-m-d h:i:s'),
                    "updated_at"          => date('Y-m-d h:i:s'),
                ];
            }

            UserProject::insert($projectDataInput);
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Project Information has been created successfully"
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
     * @param  \App\Models\UserProject  $userProject
     * @return \Illuminate\Http\Response
     */
    public function show(UserProject $userProject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserProject  $userProject
     * @return \Illuminate\Http\Response
     */
    public function edit(UserProject $userProject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProject  $userProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserProject $userProject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserProject  $userProject
     * @return \Illuminate\Http\Response
     */
    public function destroy($projectId)
    {
        $user_id = Auth::user()->id ? Auth::user()->id : 0;
        $user_info = User::where("id", $user_id)->first();

        if(empty($user_info)){
            return response()->json([
                'success' => false,'data' => [],'message' => "User not found!"
            ], Response::HTTP_NOT_FOUND);
        }

        try{
            $userProject=UserProject::where(['user_id'=>$user_id,'id'=>$projectId])->first();

            if (empty($userProject))
            {
                return response()->json([
                    'success' => false,'data' => [],'message' => "Project not found!"
                ], Response::HTTP_NOT_FOUND);
            }

            $userProject->delete();

            return response()->json([
                'success' => true,'data' => [],'message' => "Your Project has been Removed successfully!"
            ], Response::HTTP_NOT_FOUND);

        }catch (\Exception $e)
        {
            return response()->json([
                'success' => false, 'data' => [],'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
