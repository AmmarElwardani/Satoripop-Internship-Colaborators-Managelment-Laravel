<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="skill",
 *      title="Skill",
 *      description="Skill object",
 *      type="object",
 * 
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Laravel"
 *      ),
 * 
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          example="a backend framework"
 *      ),
 * 
 *      @OA\Property(
 *          property="level",
 *          enum={"beginner", "intermediate", "advanced", "expert"}
 *      ),
 * )
*/
class Skill extends Model
{
    protected $guarded = [
        
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
