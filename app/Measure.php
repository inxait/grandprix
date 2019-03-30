<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    protected $fillable = [
        'name', 'units', 'type'
    ];

    public function fulfillments()
    {
        return $this->hasMany('App\Fulfillment');
    }
}
