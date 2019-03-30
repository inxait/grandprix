<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FulfillmentResult extends Model
{
    protected $table = 'fulfillment_results';

    protected $fillable = [
        'current_percent', 'current_value', 'reference', 'fulfillment_id'
    ];

    public function fulfillment()
    {
        return $this->belongsTo('App\Fulfillment');
    }
}
