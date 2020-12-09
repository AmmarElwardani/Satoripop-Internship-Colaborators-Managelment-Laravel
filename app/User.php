<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *      schema="user",
 *      title="User",
 *      description="User object",
 *      type="object",
 * 
 *      @OA\Property(property="firstName", type="string", example="Ammar"),
 *      @OA\Property(property="lastName", type="string", example="El-wardani"),
 *      @OA\Property(property="email", type="string", format="email", example="ammarelw@gmail.com"),
 *      @OA\Property(property="password", type="string"),
 *      @OA\Property(property="phone", type="string", example="55123456"),
 *      @OA\Property(property="birthDate", type="string", format="dateTime", example="1997-05-05"),
 *      @OA\Property(property="address", type="string", type="string"),
 *      @OA\Property(property="gender", type="boolean", example="male=1/female=0"),
 *      @OA\Property(property="civilStatus", type="string", example="single"),
 *      @OA\Property(property="nCin", type="string"),
 *      @OA\Property(property="nCnss", type="string"),
 *      @OA\Property(property="nPassport", type="string"),
 *      @OA\Property(property="nationality", type="string", example="Tunisian"),
 *      @OA\Property(property="school", type="string", example="Higher institute of applied science and technology of sousse"),
 *      @OA\Property(property="history", type="text"),
 *      @OA\Property(property="source", type="string"),
 *      @OA\Property(property="position", enum={"web developer", "frontEnd Developer", "backEnd Developer", "mobile Developer", "IT", "network Security", "sales"}),
 *      @OA\Property(property="experienceLevel", type="string"),
 *      @OA\Property(property="hiringDate", type="string", format="dateTime"),
 *      @OA\Property(property="endOfContractDate", type="string", format="dateTime"),
 *      @OA\Property(property="contractType", type="string", enum={"option 1", "option 2", "option 3"}),
 *      @OA\Property(property="department_id", type="integer", example=1)
 * )
*/


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $casts = [
        'hiringDate' => 'Y-m-d',
        'endOfContractDate' => 'Y-m-d',
        'deleted_at' => 'Y-m-d',
        'email_verified_at' => 'datetime',
    ];
    
    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function evaluations(){
        return $this->hasMany('App\Evaluation');
    }

    public function leaves()
    {
        return $this->hasMany('App\Leave');
    }

    public function skills()
    {
        return $this->belongsToMany('App\Skill')->withPivot('level');
    }

    public function supervising(){
        return $this->hasMany('App\User');
    }

    public function supervised(){
        return $this->belongsTo('App\User');
    }

    public function isSuperAdmin(){

        return $this->hasRole('Super Admin');
    }
}
