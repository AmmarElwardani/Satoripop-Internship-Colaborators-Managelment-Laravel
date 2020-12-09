<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaveValidation;
use App\Leave;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveController extends Controller
{
/**
 * @OA\Parameter(
 *      parameter="leave_id",
 *      name="leave_id",
 *      description="Leave ID",
 *      in="path",
 *      required=true,
 *      @OA\Schema(
 *          type="integer",
 *          format="int64"
 *      )
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/leaves",
 *      @OA\Parameter(ref="#/components/parameters/user_id"),
 * ),
 * 
 * @OA\PathItem(
 *      path="/api/manageCollaborator/{user_id}/leaves/{leave_id}",
 *      @OA\Parameter(ref="#/components/parameters/user_id"),
 *      @OA\Parameter(ref="#/components/parameters/leave_id")
 * ),
 * 
 * @OA\RequestBody(
 *      request="leaveRequestBody",
 *      required=true,
 *      @OA\JsonContent(ref="#/components/schemas/leave")
 * )
*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLeave($user_id){
        
        $currentDate = Carbon::now();
        $nbDays = DB::table('leaves')
                        ->join('user_leave', 'user_leave.leave_id', '=', 'leaves.id')
                        ->join('users', 'users.id', '=', 'user_leave.user_id')
                        ->where('user_leave.user_id', $user_id)
                        ->whereYear('leaves.startingFromDate', '=', now()->year)
                        ->sum('nbDays');
        
        return $nbDays;
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Post(
     *      path="/api/manageCollaborator/addLeave",
     *      tags={"Leaves"},
     *      summary="Add a new leave to a user",
     *      description="Add a new leave to a user",
     *      @OA\requestBody(ref="#/components/requestBodies/leaveRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function store(LeaveValidation $request)
    {
        if(auth()->user()->can('add-user')){
            //check leave doesn't work properly
            $validated = $request->validated();

            Leave::create(
                $validated
            );

            
            return response()->json([
                'message' => 'Leave created!'
            ], 201);

            // if( ($this->checkLeave($request->input('user_id')) + $request->input('nbDays') ) < 15) {
            //     $leave = new Leave([
            //                         'startingFromDate' => Carbon::parse($request->input('startingFromDate'))->format('Y-m-d H:I:s'),
            //                         'nbDays' => $request->input('nbDays'),
            //                         ]);
            //     $leave->save();
            //     $leave->users()->attach($request->input('user_id'));
                
            //     return response()->json(compact('leave'));
            // }
        }
        return 0;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *      path="/api/manageCollaborator/leave/show/{user_id}",
     *      tags={"Leaves"},
     *      summary="Get user leaves",
     *      description="Get all user leaves",
     *      @OA\Response(
     *          response=200,
     *          description="User leaves",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/leave")
     *          )
     *      ),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized")
     * )
    */
    public function show($id)
    {
        if(auth()->user()->can('view')){
            $user = User::findOrFail($id);
            
            $data = $user->leaves;
            
            return response()->json(compact('data'));
        }

        return 'unauthorized';
    }
    
  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
        /**
     * @OA\Put(
     *      path="/api/manageCollaborator/updateLeave/{user_id}/{leave_id}",
     *      tags={"Leaves"},
     *      summary="Update user leave",
     *      description="Update specified user leave",
     *      @OA\RequestBody(ref="#/components/requestBodies/leaveRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function update(LeaveValidation $request, User $user, Leave $leave)
    {
        if(auth()->user()->can('update-user')) {
            $leave->update($request->validated());
            return response()->json([], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *      path="/api/manageCollaborator/deleteLeave/{leave_id}",
     *      tags={"Leaves"},
     *      summary="Delete a specified leave from storage",
     *      description="Delete a specified leave from storage",
     *      @OA\RequestBody(ref="#/components/requestBodies/leaveRequestBody"),
     *      @OA\Response(response=200, ref="#/components/responses/success"),
     *      @OA\Response(response=401, ref="#/components/responses/unauthenticated"),
     *      @OA\Response(response=403, ref="#/components/responses/unauthorized"),
     * )
    */
    public function destroy($id)
    {
        if(auth()->user()->can('delete-user')){
            $leave = Leave::findOrFail($id);
            $leave->delete();
            
            return 1;
        }
        return 0;
    }

    
}
