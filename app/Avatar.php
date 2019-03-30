<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $fillable = [
        'helmet', 'gloves', 'uniform', 'shoes', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
