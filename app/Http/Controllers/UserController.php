<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\User;
use JWTAuth;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\UserValidation;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view-user')){

            if (request('q') != null) {
                $user = User::where('firstName', 'like', '%'.request('q').'%')->get();
                return UserResource::collection($user);;
            }else{
               return $this->refresh();
            }
            // return $user->department->name;
        
        }
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserValidation $request)
    {

        if(auth()->user()->can('add-user')){
            $validated = $request->validated();
    
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create(
                $validated
            );
            // $token = JWTAuth::fromUser($user);
    
            return response()->json(['collaborator_id' => $user->id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->can('view')){

            $user = User::findOrFail($id);
        
            return response()->json($user);
        } 
   }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserValidation $request, $id)
    {
        if(auth()->user()->can('update-user')){


            $validated = $request->validated();
            
            $user = User::findOrFail($id);

            if($validated['password'] === null) {
                $validated['password'] = $user->password;
            } else {
                $validated['password'] = Hash::make($validated['password']);
            }
    
            $user->update($validated);
    
            return response()->json($user, 201);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if (auth()->user()->can('delete-user')){
            $user = User::findOrFail($id);
            $user->delete();
            
            return 1;
        }
        return 0;
    }

    public function GenderCount(){

        
        $genderStat = collect([
            'male' => User::all()->where('gender', 1)->count(),
            'female' => User::all()->where('gender', 0)->count()
        ]);

        return $genderStat;
    }


    public function DepartmentStats(){

        $departmentsStat = collect([]);

        $departments = Department::all();

        foreach ($departments as $department) {
            $departmentsStat -> push([
                'Id' => $department->name,
                'Nb' => $department->users->count()
            ]);
        };
    
        return $departmentsStat;
    }

    
    private function refresh(){
        $user = User::orderBy('created_at', 'DESC')->paginate(4);

        return  UserResource::collection($user);
    }

    public function archive(){
        $archive = User::onlyTrashed()->get();

        return UserResource::collection($archive);
    }

    public function restore($id) {
        $user = User::withTrashed()->where('id', $id)->restore();

        return response()->json(['message' => 'User restored.'], 200);
    }

    public function deletePermantly($id) {
        User::onlyTrashed()->where('id', $id)->forceDelete();

        return response()->json(['message' => 'User permantly deleted.'], 200);
    }

}
