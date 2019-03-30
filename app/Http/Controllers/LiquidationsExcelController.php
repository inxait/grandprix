<?php

namespace App\Http\Controllers;

use App\LiquidationUser;
use App\Measure;
use App\Helpers\Settings;
use Excel;
use Illuminate\Http\Request;
use stdClass;

class LiquidationsExcelController extends Controller
{
    public static function download($liquidation, $usersToLiquidate)
    {
        $now = date('Y-m-d H:i:s');
        $fileName = $now.'_'.$liquidation->name;

        return Excel::create($fileName, function($excel) use ($liquidation, $usersToLiquidate) {
            $excel->sheet('Liquidación de ventas', function($sheet) use ($liquidation, $usersToLiquidate) {
                $sheet->setStyle(array(
                    'font' => array(
                        'name' =>  'Arial',
                        'size' =>  11,
                    )
                ));

                $sheet->row(1, array(
                    'Distribuidor', 'Cédula asesor', 'Nombres', 'Apellidos', 'Activo en la plataforma', 'Periodo de venta',
                    'Porcentaje de cumplimiento', 'Meta de cumplimiento', 'Neto de ventas',
                    'Total de ventas', 'Porcentaje sobre ventas', 'Valor a entregar'
                ));

                $sheet->cells('A1:L1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                foreach ($usersToLiquidate as $row) {
                    $sheet->appendRow(array(
                        $row->seller->companies()->first()->name,
                        $row->seller->identification,
                        $row->seller->first_name,
                        $row->seller->last_name,
                        ($row->seller->updated_data) ? 'Si': 'No',
                        $row->reference,
                        $row->current_percent,
                        intval($row->goal),
                        $row->current_value,
                        $row->totalSales,
                        $liquidation->percent_to_give,
                        $row->receiveTotal
                    ));

                    $history = new stdClass;
                    $history->user_id = $row->seller->id;
                    $history->user_first_name = $row->seller->first_name;
                    $history->user_last_name = $row->seller->last_name;
                    $history->user_identification = $row->seller->identification;
                    $history->reference = $row->reference;
                    $history->current_percent = $row->current_percent;
                    $history->goal = $row->goal;
                    $history->current_value = $row->current_value;
                    $history->total_sales = $row->totalSales;
                    $history->receive_total = $row->receiveTotal;
                    $history->percent_to_give = $liquidation->percent_to_give;
                    $history->unit_val = null;
                    $measure = Measure::find($liquidation->measure_id);

                    if ($measure->units == 'galones') {
                        $history->unit_val = Settings::getByKey('valor-unidad-lubricantes')->value;
                    }

                    if ($measure->units == 'unidades') {
                        $history->unit_val = Settings::getByKey('valor-unidad-baterias')->value;
                    }

                    LiquidationUser::create([
                        'liquidation_id' => $liquidation->id,
                        'user_id' => $row->seller->id,
                        'history' => json_encode($history)
                    ]);
                }

                $sheet->freezeFirstRow();
            });
        })->export('xlsx');
    }
}
