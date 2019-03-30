<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'title', 'description', 'seconds_to_answer', 'answered_val'
    ];

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    public function trivias()
    {
        return $this->belongsToMany('App\Trivia', 'trivia_question');
    }
}
