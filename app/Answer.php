<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'description', 'correct', 'question_id'
    ];

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    protected $hidden = [
        'correct'
    ];
}
