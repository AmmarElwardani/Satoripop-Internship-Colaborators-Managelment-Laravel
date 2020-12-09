<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *      schema="evaluation",
 *      title="evaluation",
 *      description="Evaluation object",
 *      
 *      @OA\Property(property="type", type="string", example="Monthly test"),
 *      @OA\Property(property="evalDate", type="string", format="dateTime", example="2020-10-05"),
 *      @OA\Property(property="result", type="string", example="passed")
 * )
*/
class Evaluation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'evalDate' => 'Y-m-d',
    ];

    public function users(){
        return $this->belongsTo('App\User');
    }
}
