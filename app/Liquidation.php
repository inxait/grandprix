<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidation extends Model
{
    protected $fillable = [
        'name', 'percent_to_give', 'measure_id', 'month'
    ];
}
