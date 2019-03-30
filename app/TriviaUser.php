<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TriviaUser extends Model
{
    protected $table = 'trivia_user';

    protected $fillable = [
        'trivia_id', 'user_id', 'time_to_respond', 'correctness_percent'
    ];
}
