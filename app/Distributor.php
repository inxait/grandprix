<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $fillable = [
        'company_agile_id', 'name', 'nit'
    ];

    public function sellers()
    {
        return $this->belongsToMany('App\User');
    }
}
