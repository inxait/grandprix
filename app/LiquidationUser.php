<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiquidationUser extends Model
{
    protected $table = 'liquidation_user';

    protected $fillable = [
        'liquidation_id', 'user_id', 'history'
    ];
}
