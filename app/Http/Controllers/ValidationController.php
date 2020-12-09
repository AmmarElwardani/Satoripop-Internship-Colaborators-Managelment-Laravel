<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvaluationValidation;
use Illuminate\Http\Request;
use App\Http\Requests\UserValidation;
use App\Http\Requests\SkillValidation;
use App\Http\Requests\LeaveValidation;

class ValidationController extends Controller
{
    public function userValidation(UserValidation $request ){

        return $request->validated();

    }

    public function skillValidation(SkillValidation $request){

        $request->validated();
        return response()->json('data valid', 200);
    }

    public function evaluationValidation(EvaluationValidation $request){
        
        $request->validated();
        return response()->json('data valid', 200);
    }

    
    public function leaveValidation(LeaveValidation $request){
        
        $request->validated();
        return response()->json('data valid', 200);
    }
}
