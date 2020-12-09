<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function (){
    Route::post('signin', 'AuthController@authenticate');
    Route::post('signout', 'AuthController@signOut')->middleware('auth:api');
    
    Route::get('currentuser', 'AuthController@currentUser');
    Route::get('user', 'AuthController@getAuthenticatedUser')->middleware('jwt.verify');
});

Route::group(['prefix' => 'user'], function () {
    Route::get('index/{q?}', 'UserController@index')->middleware('jwt.verify');
    Route::post('register', 'UserController@store');
    Route::get('show/{id}', 'UserController@show')->middleware('jwt.verify');
    Route::patch('update/{id}', 'UserController@update');
    Route::delete('delete/{id}', 'UserController@destroy')->middleware('jwt.verify');

    Route::get('archive', 'UserController@archive')->middleware('jwt.verify');
    Route::get('/{id}/restore', 'UserController@restore')->middleware('jwt.verify');
    route::delete('{id}/delete-permantly', 'UserController@deletePermantly')->middleware('jwt.verify');


    Route::get('departmentStat', 'UserController@DepartmentStats');
    Route::get('genderCount', 'UserController@GenderCount');

    
});

Route::group(['prefix' => 'manageCollaborator'], function(){
    Route::get('skill/show/{id}', 'SkillController@show')->middleware('jwt.verify');
    Route::post('addSkill', 'SkillController@store')->middleware('jwt.verify');
    Route::patch('updateSkill/{user}/{skill}', 'SkillController@update')->middleware('jwt.verify');
    Route::delete('deleteSkill/{id}', 'SkillController@destroy')->middleware('jwt.verify');
    
    Route::post('validateSkill', 'ValidationController@skillValidation')->middleware('jwt.verify');

    
    
    Route::get('evaluation/show/{id}', 'EvaluationController@show')->middleware('jwt.verify');
    Route::post('addEvaluation', 'EvaluationController@store')->middleware('jwt.verify');
    Route::patch('updateEvaluation/{user}/{evaluation}', 'EvaluationController@update')->middleware('jwt.verify');
    Route::delete('deleteEvaluation/{id}', 'EvaluationController@destroy')->middleware('jwt.verify');

    Route::post('validateEvaluation', 'ValidationController@evaluationValidation')->middleware('jwt.verify');

    Route::get('leave/show/{id}', 'LeaveController@show')->middleware('jwt.verify');
    Route::post('addLeave', 'LeaveController@store')->middleware('jwt.verify');
    Route::patch('updateLeave/{user}/{leave}', 'LeaveController@update')->middleware('jwt.verify');
    Route::delete('deleteLeave/{id}', 'LeaveController@destroy')->middleware('jwt.verify');

    Route::post('validateLeave', 'ValidationController@leaveValidation')->middleware('jwt.verify');
});

Route::get('/departments', 'DepartmentCompanyController@getDepartments');
Route::get('/companies', 'DepartmentCompanyController@getCompanies');
