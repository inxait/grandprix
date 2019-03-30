<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    /*types
    1. Activación en la plataforma
    2. Cumplimiento {metrica}
    3. Sobrecumplimiento {metrica}
    4. Participación Trivia {trivia}
    5. Puntuación custom
    */
    protected $fillable = [
        'points_event', 'value', 'month', 'year', 'type', 'user_id'
    ];
}
