<?php

namespace App\Http\Controllers;

use App\Fulfillment;
use App\Measure;
use Illuminate\Http\Request;
use App\Helpers\Calendar;

class FulfillmentsController extends Controller
{
    public static function deleteUserFulfillments($id)
    {
        $fulfillments = Fulfillment::where('user_id', $id)->get();

        foreach ($fulfillments as $item) {
            $item->results()->delete();
            $item->delete();
        }
    }

    /**
     *
     * Obtiene las metas de cada métrica del usuario para el mes actual.
     * @param int $id
     * @return array
     *
     */
    public static function getGoalsUser($id){

        $goals = [];
        $i = 0;

        $fulfillments = Fulfillment::where([
            'month' => date('m'),
            'year' => date('Y'),
            'active' => 1,
            'user_id' => $id
        ])->get();

        foreach ($fulfillments as $item) {
            if(!is_null($item)){
                //Metrica
                $measure = Measure::find($item->measure_id);
                $goals[$i]['measure'] = $measure->name;
                //Mes
                /*$month = Calendar::getHumanMonth($item->month);
                $goals[$i]['month'] = ucwords($month);*/
                //Meta
                $goals[$i]['goal'] = $item->goal;
            }
            $i++;
        }
        //Devuelve metas.
        return $goals;
    }
}
