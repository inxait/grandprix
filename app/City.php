<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = [
        'name', 'department_id'
    ];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }
}
