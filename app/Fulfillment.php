<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fulfillment extends Model
{
    protected $fillable = [
        'title', 'goal', 'min_value', 'reward', 'overcompliance_value',
        'overcompliance_reward', 'overcompliance_updated_at', 'period',
        'active', 'month', 'year', 'user_id', 'measure_id'
    ];

    public function measure()
    {
        return $this->belongsTo('App\Measure');
    }

    public function results()
    {
        return $this->hasMany('App\FulfillmentResult');
    }
}
