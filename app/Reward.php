<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'image', 'title', 'type', 'description', 'active', 'legal'
    ];

    public function trivia()
    {
        return $this->belongsTo('App\Trivia');
    }
}
