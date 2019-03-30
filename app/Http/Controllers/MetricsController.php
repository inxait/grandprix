<?php

namespace App\Http\Controllers;

use App\Fulfillment;
use App\FulfillmentResult;
use App\Helpers\Calendar;
use App\Measure;
use App\Point;
use App\Sale;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class MetricsController extends Controller
{
    public function showMetrics()
    {
        $months = Calendar::listMonths();
        $measures = Measure::all();
        $fulfillments = Fulfillment::whereNotNull('goal')->paginate(20);
        $totalFulfillments = DB::select('SELECT COUNT(*) AS total FROM fulfillments WHERE goal IS NOT NULL');

        foreach ($fulfillments as $item) {
            $item->user = User::find($item->user_id)->identification;
        }

        return view('pages.admin.list-measures')
             ->with(compact('measures', 'fulfillments', 'months', 'totalFulfillments'));
    }

    public function showCreateFulfillment()
    {
        $measures = Measure::all();

        return view('pages.admin.create-fulfillment')->with(compact('measures'));
    }

    public function createFulfillment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'goal' => 'required',
            'reward' => 'required',
            'measure' => 'required|integer',
            'period' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $now = Carbon::now();

        $fulfillment = Fulfillment::create([
            'title' => $request->input('title'),
            'goal' => $request->input('goal'),
            'reward' => $request->input('reward'),
            'year' => $now->year,
            'overcompliance_value' => $request->input('overcompliance_value'),
            'overcompliance_reward' => $request->input('overcompliance_reward'),
            'period' => $request->input('period'),
            'measure_id' => $request->input('measure'),
        ]);

        return redirect('dashboard/metricas')->with('status', 'Cumplimiento creado correctamente.');
    }

    public static function calcFulfillmentsIfPossible()
    {
        $now = Carbon::now();
        $fulfillments = Fulfillment::where('active', true)->where('year', $now->year)->get();

        foreach ($fulfillments as $fulfillment) {
            $results = FulfillmentResult::where('fulfillment_id', $fulfillment->id)->get();
            //Calc sales only when needed
            if (!count($results)) {
                $sales = Sale::where('measure_id', $fulfillment->measure_id)
                         ->where('month', $fulfillment->month)
                         ->where('year', $now->year)
                         ->where('user_id', $fulfillment->user_id)
                         ->whereNotNull('value')->get();

                if (!is_null($fulfillment->goal)) {
                    if ($fulfillment->period == 'Mensual') {
                       self::calcMonthSales($fulfillment, $sales);
                    }
                }
            }
        }
    }

    private static function calcMonthSales($fulfillment, $sales)
    {
        $periodResults = [];

        foreach ($sales as $sale) {
            $percent = 0;

            if ($fulfillment->goal > 0) {
                $percent = $sale->value * 100 / intval($fulfillment->goal);
            }

            FulfillmentResult::create([
                'reference' => Calendar::getHumanMonth($sale->month),
                'fulfillment_id' => $fulfillment->id,
                'current_percent' => $percent,
                'current_value' => $sale->value,
            ]);

            //cumplimiento
            if (intval($sale->value) >= intval($fulfillment->goal)) {
                Point::updateOrCreate([
                    'points_event' => $fulfillment->title.' '.Calendar::getHumanMonth($sale->month),
                    'type' => 2,
                    'month' => $sale->month,
                    'year' => $sale->year,
                    'user_id' => $fulfillment->user_id
                ], [
                    'value' => $fulfillment->reward,
                ]);

                //sobrecumplimiento
                $overVal = intval($sale->value) - intval($fulfillment->goal);
                if ($overVal >= intval($fulfillment->overcompliance_value)) {
                    $pointsToGive = floor($overVal / intval($fulfillment->overcompliance_value)) * $fulfillment->overcompliance_reward;

                    Point::updateOrCreate([
                        'points_event' => 'Sobre'.mb_strtolower($fulfillment->title).' '.Calendar::getHumanMonth($sale->month),
                        'type' => 3,
                        'month' => $sale->month,
                        'year' => $sale->year,
                        'user_id' => $fulfillment->user_id
                    ], [
                        'value' => $pointsToGive,
                    ]);
                }
            }
        }
    }
}
