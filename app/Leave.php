<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *      schema="leave",
 *      title="Leave",
 *      description="Leave object",
 *      
 *      @OA\Property(property="startingFromDate", type="string", format="dateTime", example="2020-10-05"),
 *      @OA\Property(property="nbDays", type="integer", example=3)
 * )
*/

class Leave extends Model
{
    protected $guarded = [];

    protected $casts = [
        'startingFromDate' => 'Y-m-d',
    ];

    protected $maxDays = 15;

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
