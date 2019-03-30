<?php

namespace App\Helpers;

use App\Fulfillment;
use App\Measure;
use Carbon\Carbon;

class Fulfillments
{
    public static function getUserMonthGoals($id)
    {
        $now = Carbon::now();
        $goals = Fulfillment::where('month', $now->month)->where('year', $now->year)->where('user_id', $id)->get();

        foreach ($goals as $item) {
            $item->measure = Measure::find($item->measure_id);
        }

        return $goals;
    }
}
