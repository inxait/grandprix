<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trivia extends Model
{
    protected $fillable = [
        'name', 'description', 'start_date', 'finish_date', 'active', 'total_val',
        'points_all_correct', 'points_some_correct', 'points_per_answer',
        'allow_percent_points', 'study_information', 'historic', 'reward_id',
        'number_questions_random'
    ];

    public function questions()
    {
        return $this->belongsToMany('App\Question', 'trivia_question');
    }
}
