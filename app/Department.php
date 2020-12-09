<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
    ]; 

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function Company()
    {
        return $this->belongsTo('App\Company');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
