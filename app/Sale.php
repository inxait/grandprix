<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'month', 'year', 'value', 'user_id', 'measure_id'
    ];
}
