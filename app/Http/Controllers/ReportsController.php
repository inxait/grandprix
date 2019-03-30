<?php

namespace App\Http\Controllers;

use App\Distributor;
use App\Fulfillment;
use App\FulfillmentResult;
use App\Helpers\Calendar;
use App\Liquidation;
use App\LiquidationUser;
use App\Measure;
use App\User;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function showCreateReports()
    {
        $months = Calendar::listMonths();
        return view('pages.admin.list-reports')->with(compact('months'));
    }

    public function createReport(Request $request)
    {
        $month = $request->input('month');
        $liquidations = Liquidation::where('name', 'LIKE', '%'.ucfirst($month).'%')->get();

        if (count($liquidations)) {
            $this->createReportExcel($month);
        } else {
            return back()->withErrors(['No se encontraron liquidaciones del mes.']);
        }
    }

    private function createReportExcel($month)
    {
        $date = Carbon::now();
        $fileName = $date.'_reporte_'.$month;

        return Excel::create($fileName, function($excel) use ($month) {
            $monthNumber = Calendar::getNumberMonth($month);
            $now = Carbon::now();
            $measures = Measure::all();

            foreach ($measures as $measure) {
                $fulfillments = Fulfillment::where('month', $monthNumber)
                                           ->where('year', $now)
                                           ->where('measure_id', $measure->id)->get();

                foreach ($fulfillments as $item) {
                    $item->user = User::find($item->user_id);
                    $item->company = (object)['name' => '-'];
                    if (!is_null($item->user->companies()->first())) {
                        $item->company = $item->user->companies()->first();
                    }
                    $userFulfill = FulfillmentResult::where('fulfillment_id', $item->id)->first();
                    if (!is_null($userFulfill)) {
                        $item->current_percent = $userFulfill->current_percent;
                        $item->current_value = $userFulfill->current_value;
                    }
                }

                $excel->sheet($measure->name, function($sheet) use ($fulfillments, $month) {
                    $sheet->setStyle(array(
                        'font' => array(
                            'name' =>  'Arial',
                            'size' =>  11,
                        )
                    ));

                    $sheet->cells('A1:H1', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    $sheet->row(1, array(
                        'Distribuidor',
                        'Cédula asesor', 'Nombres', 'Apellidos', 'Periodo de venta',
                        'Porcentaje de cumplimiento', 'Meta de cumplimiento', 'Neto de ventas'
                    ));

                    foreach ($fulfillments as $row) {
                        $sheet->appendRow(array(
                            $row->company->name,
                            $row->user->identification,
                            $row->user->first_name,
                            $row->user->last_name,
                            $month,
                            $row->current_percent,
                            intval($row->goal),
                            $row->current_value,
                        ));
                    }

                    $sheet->freezeFirstRow();
                });
            }
        })->export('xlsx');
    }
}
