<?php

namespace App\Http\Controllers;

use App\Fulfillment;
use App\FulfillmentResult;
use App\Liquidation;
use App\LiquidationUser;
use App\Measure;
use App\User;
use App\Helpers\Calendar;
use App\Helpers\Settings;
use Illuminate\Http\Request;
use Validator;

class LiquidationsController extends Controller
{
    public function index()
    {
        $liquidations = Liquidation::all();

        foreach ($liquidations as $item) {
            $measure = Measure::find($item->measure_id);
            $item->measure = $measure->name;
        }

        return view('pages.admin.list-liquidations')->with(compact('liquidations'));
    }

    public function showCreate()
    {
        $months = Calendar::listMonths();
        $measures = Measure::all();

        return view('pages.admin.create-liquidation')->with(compact('measures', 'months'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'liquidation_name' => 'required|string|max:100',
            'liquidation_percent' => 'required',
            'month' => 'required',
            'measure_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        Liquidation::create([
            'name' => $request->input('liquidation_name'),
            'percent_to_give' => $request->input('liquidation_percent'),
            'measure_id' => $request->input('measure_id'),
            'month' => $request->input('month'),
        ]);

        return redirect('dashboard/liquidaciones')->with('status', 'Se ha creado la liquidación correctamente');
    }

    public function calculateLiquidation($id)
    {
        MetricsController::calcFulfillmentsIfPossible();

        $liquidation = Liquidation::find($id);

        if (!is_null($liquidation)) {
            $usersToLiquidate = [];
            $measure = Measure::find($liquidation->measure_id);
            $fulfillments = $measure->fulfillments()->where('active', true)->where('month', $liquidation->month)->get();

            foreach ($fulfillments as $fulfill) {
                $userFulfill = FulfillmentResult::where('fulfillment_id', $fulfill->id)->get();

                foreach ($userFulfill as $item) {
                    if (intval($item->current_percent) >= 100) {
                        $item->seller = User::find($fulfill->user_id);
                        $item->goal = $fulfill->goal;
                        array_push($usersToLiquidate, $item);
                    }
                }
            }

            $units = $measure->units;

            if ($units == 'galones') {
                foreach ($usersToLiquidate as $item) {
                    $unitVal = Settings::getByKey('valor-unidad-lubricantes')->value;
                    $item->totalSales = $item->current_value * $unitVal;
                    $item->receiveTotal = (($item->current_value * $unitVal) * $liquidation->percent_to_give) / 100;
                }
            }

            if ($units == 'unidades') {
                foreach ($usersToLiquidate as $item) {
                    $unitVal = Settings::getByKey('valor-unidad-baterias')->value;
                    $item->totalSales = $item->current_value * $unitVal;
                    $item->receiveTotal = (($item->current_value * $unitVal) * $liquidation->percent_to_give) / 100;
                }
            }

            if ($units == 'pesos') {
                foreach ($usersToLiquidate as $item) {
                    $item->totalSales = $item->current_value;
                    $item->receiveTotal = ($item->current_value  * $liquidation->percent_to_give) / 100;
                }
            }

            return LiquidationsExcelController::download($liquidation, $usersToLiquidate);
        }
    }
}
