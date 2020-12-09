<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillValidation;
use App\Http\Resources\SkillResource;
use App\Skill;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


/**
 * @OA\requestBody(
 *      request="skillRequestBody",
 *      required=true,
 *      description="Skill request",
 *      @OA\JsonContent(ref="#/components/schemas/skill")
 * ),
 * 
 * @OA\Parameter(
 *      parameter="skill_id",
 *      name="skill_id",
 *      description="Skill ID",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64"
 *      )
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/skills",
 *      @OA\parameter(ref="#/components/parameters/user_id"),
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/skills/{skill_id}",
 *      @OA\parameter(ref="#/components/parameters/user_id"),
 *      @OA\parameter(ref="#/components/parameters/skill_id"),
 * ),
*/
class SkillController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Post(
     *      path="/api/manageCollaborator/addSkill",
     *      tags={"Skills"},
     *      summary="Assign a skill to a user",
     *      description="Assign a skill to a user",
     *      @OA\requestBody(ref="#/components/requestBodies/skillRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *      @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
    */
    public function store(SkillValidation $request)
    {   
        if(auth()->user()->can('add-user')){
            $validated = $request->validated();
            $skill = Skill::where('name', $validated['name'])->first();

            if(! $skill) {
                $skill = Skill::create([
                    'name' => $validated['name'],
                ]);
            }
            
            $user = User::findOrFail($request->user_id);
            $user->skills()->attach($skill, ['level' => $validated['level']]);

            return response()->json([
                'message' => 'Skill created!'
            ], 201);
        }
        
        return response()->json([], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    
     /**
     * @OA\Get(
     *      path="/api/manageCollaborator/skill/show/{user_id}",
     *      tags={"Skills"},
     *      summary="User skills",
     *      description="Get user skills",
     *      @OA\RequestBody(ref="#/components/requestBodies/skillRequestBody"),
     *      @OA\Response(
     *          response=200,
     *          description="User skills list",
     *          @OA\JsonContent(ref="#/components/schemas/skill")
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *      @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
    */
    public function show($id)
    {
        if(auth()->user()->can('view-user')){
            $user = User::findOrFail($id);
            
            $skills = $user->skills;
            
            return response()->json(
                SkillResource::collection($skills), 200);
        }

            return response([], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */

     
    /**
     * @OA\Put(
     *      path="/api/manageCollaborator/updateSkill/{user_id}/{skill_id}",
     *      tags={"Skills"},
     *      summary="Edit user skill",
     *      description="Update a skill for a user",
     *      @OA\requestBody(ref="#/components/requestBodies/skillRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     *      @OA\Response(response=422, ref="#/components/responses/invalid-data")
     * )
    */
    public function update(SkillValidation $request, User $user, Skill $skill)
    {
        if(auth()->user()->can('update-user')){
            $validated = $request->validated();
            
            if($skill->name !== $validated['name']) {
                $user->skills()->detach($skill);
                
                if(count($skill->users) === 0) // delete skill if no user has it 
                    $skill->delete();
    
                $newSkill = Skill::create(['name' => $validated['name']]);
                $user->skills()->attach($newSkill, ['level' => $validated['level']]);
            }else {
                $user_skill_pivot = $user->skills()->wherePivot('skill_id', $skill->id)->first()->pivot;
                $user_skill_pivot->update(['level' => $validated['level']]);
            }
    
            return response()->json([], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */

      /**
     * @OA\Delete(
     *      path="/api/manageCollaborator/deleteSkill/{skill_id}",
     *      tags={"Skills"},
     *      summary="Revoke a skill from a user",
     *      description="Revoke a skill to a user and delete it if no user still have it",
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function destroy($id)
    {
        if (auth()->user()->can('delete-user')){
            $skill = Skill::findOrFail($id);
            $skill->delete();
            
            return response()->json([], 200);
        }
        return response()->json([],403);
    }
}
