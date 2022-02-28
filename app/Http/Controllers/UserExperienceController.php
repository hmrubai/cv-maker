<?php

namespace App\Http\Controllers;

use App\Models\UserExperience;
use Illuminate\Http\Request;
use Auth,Validator,DB;
use App\User;

use phpDocumentor\Reflection\Types\Null_;
use function PHPUnit\Framework\isNull;
use Symfony\Component\HttpFoundation\Response;

class UserExperienceController extends Controller
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
            return response()->json(['success' => false,'data' => [],'message' => "User not found!" ], Response::HTTP_NOT_FOUND);
        }

        $userExperiences = UserExperience::where('user_id', $user_id)->get();
        return response()->json([
            'success' => true, 'data' => $userExperiences, 'message' => "Experience List ."
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
            return response()->json(['success' => false,'data' => [],'message' => "User not found!"], Response::HTTP_NOT_FOUND);
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'experience_data' => 'required|array|min:1',
            'experience_data.*.organization' => 'required|max:150',
            'experience_data.*.designation' => 'required|max:150',
            'experience_data.*.from_date' => 'required|date',
            'experience_data.*.is_left_job' => 'nullable|in:0,1',
            'experience_data.*.to_date' => 'nullable|date',
            'experience_data.*.is_still_active' => 'nullable|in:0,1',
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

            $isExistExperience = UserExperience::where('user_id', $user_id)->first();
            if(!empty($isExistExperience)){
                UserExperience::where('user_id', $user_id)->delete();
            }

            foreach ($request->experience_data as $key=>$experience){

                $toDate=Null;

                if ($experience['is_left_job']==1 && !empty($experience['to_date'])){
                    $toDate=date('Y-m-d',strtotime($experience['to_date']));
                }

                $experienceInput[]=[
                    "user_id"       => $user_id,
                    "organization"     => $experience['organization'],
                    "designation"     => $experience['designation'],
                    "from_date"          => date('Y-m-d',strtotime($experience['from_date'])),
                    "is_left_job"  => $experience['is_left_job']?$experience['is_left_job']:0,
                    "to_date"          => $toDate,
                    "is_still_active"   => $experience['is_still_active']?$experience['is_still_active']:0,
                    "created_at"    => date('Y-m-d h:i:s'),
                    "updated_at"    => date('Y-m-d h:i:s'),
                ];
            }

            UserExperience::insert($experienceInput);
            DB::commit();

            return response()->json(['success' => true,'data' => [],'message' => "Experience Information has been created successfully"
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
     * @param  \App\Models\UserExperience  $userExperience
     * @return \Illuminate\Http\Response
     */
    public function show(UserExperience $userExperience)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserExperience  $userExperience
     * @return \Illuminate\Http\Response
     */
    public function edit(UserExperience $userExperience)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserExperience  $userExperience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserExperience $userExperience)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserExperience  $userExperience
     * @return \Illuminate\Http\Response
     */
    public function destroy($experienceId)
    {
        $user_id = Auth::user()->id ? Auth::user()->id : 0;
        $user_info = User::where("id", $user_id)->first();

        if(empty($user_info)){
            return response()->json([
                'success' => false,'data' => [],'message' => "User not found!"
            ], Response::HTTP_NOT_FOUND);
        }

        try{
            $userExperience=UserExperience::where(['user_id'=>$user_id,'id'=>$experienceId])->first();

            if (empty($userExperience))
            {
                return response()->json([
                    'success' => false,'data' => [],'message' => "Experience not found!"
                ], Response::HTTP_NOT_FOUND);
            }

            $userExperience->delete();

            return response()->json([
                'success' => true,'data' => [],'message' => "Your Experience has been Removed successfully!"
            ], Response::HTTP_NOT_FOUND);

        }catch (\Exception $e)
        {
            return response()->json([
                'success' => false, 'data' => [],'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
