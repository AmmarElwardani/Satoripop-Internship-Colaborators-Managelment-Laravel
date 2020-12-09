<?php

namespace App\Http\Controllers;
use App\Http\Requests\EvaluationValidation;
use App\User;
use App\Evaluation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvaluationController extends Controller
{
    
/**
 * @OA\Parameter(
 *      parameter="eval_id",
 *      name="eval_id",
 *      description="Evaluation ID",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64"
 *      )
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/evaluation",
 *      @OA\Parameter(ref="#/components/parameters/user_id"),
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/evaluation/{eval_id}",
 *      @OA\Parameter(ref="#/components/parameters/user_id"),
 *      @OA\Parameter(ref="#/components/parameters/eval_id")
 * ),
 * 
 * @OA\RequestBody(
 *      request="evaluationsRequestBody",
 *      required=true,
 *      @OA\JsonContent(ref="#/components/schemas/evaluation")
 * )
*/


     /**
     * @OA\Post(
     *      path="/api/manageCollaborator/addEvaluation",
     *      tags={"evaluations"},
     *      summary="Add a new evaluation to a user",
     *      description="Add a new evaluation to a user",
     *      @OA\requestBody(ref="#/components/requestBodies/evaluationsRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function store(EvaluationValidation $request)
    {
        if(auth()->user()->can('add-user')){

            $validated = $request->validated();
            
            // return response()->json($validated);

            Evaluation::create(
                $validated
            );

            
            return response()->json([
                'message' => 'Evaluation created!'
            ], 201);
        }
        
        return response()->json([], 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *      path="/api/manageCollaborator/evaluation/show/{user_id}",
     *      tags={"evaluations"},
     *      summary="Get user evaluations",
     *      description="Get all user evaluations",
     *      @OA\Response(
     *          response=200,
     *          description="User evaluations",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/evaluation")
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function show($id)
    {
        if(auth()->user()->can('view-user')){
            $user = User::findOrFail($id);

            $data = $user->evaluations;
            
            return response()->json(compact('data'));
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
    /** @OA\Put(
        *      path="/api/manageCollaborator/updateEvaluation/{user_id}/{eval_id}",
        *      tags={"evaluations"},
        *      summary="Update user evaluation",
        *      description="Update specified user evaluation",
        *      @OA\RequestBody(ref="#/components/requestBodies/evaluationsRequestBody"),
        *      @OA\Response(response=200, ref="#/components/responses/success"),
        *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
        *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
        * )
       */
    public function update(EvaluationValidation $request, User $user, Evaluation $evaluation)
    {
        if(auth()->user()->can('update-user')){
           
            
            $evaluation->update($request->validated());
            
            return response()->json([], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evaluation  $evaluation
     * @return \Illuminate\Http\Response
     */
      /**
     * @OA\Delete(
     *      path="/api/manageCollaborator/deleteEvaluation/{eval_id}",
     *      tags={"evaluations"},
     *      summary="Delete a specified evaluation from storage",
     *      description="Delete a specified evaluation from storage",
     *      @OA\RequestBody(ref="#/components/requestBodies/evaluationsRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function destroy($id)
    {
        if (auth()->user()->can('delete-user')){
            $evaluation = Evaluation::findOrFail($id);
            $evaluation->delete();
            
            return 1;
        }
        return 0;
    }
}
