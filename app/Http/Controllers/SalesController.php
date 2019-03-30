<?php

namespace App\Http\Controllers;

use App\Fulfillment;
use App\Measure;
use App\Sale;
use App\User;
use App\Helpers\Calendar;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Validator;

class SalesController extends Controller
{
    public function showLoadSales()
    {
        $sales = Sale::whereNotNull('value')->orderBy('created_at', 'desc')->paginate(20);
        $totalSales = DB::select('SELECT COUNT(*) AS total FROM sales WHERE value IS NOT NULL');

        foreach ($sales as $sale) {
            $sale->seller = User::find($sale->user_id);
            $sale->distributor = $sale->seller->companies()->first()->name;
            $sale->measure = Measure::find($sale->measure_id);
        }

        return view('pages.admin.list-sales')->with(compact('sales', 'totalSales'));
    }

    public function uploadSales(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_excel' => 'required|file',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('sales_excel');
        $errors = $this->validateExcel($file);

        if (count($errors)) {
            return back()->withErrors((object) ['excel_errors' => $errors]);
        } else {
            $this->storeExcel($file);

            return back()->with('status', 'Carga de ventas realizado correctamente.');
        }
    }

    public static function deleteUserSales($id)
    {
        Sale::where('user_id', $id)->delete();
    }

    private function validateExcel($file)
    {
        set_time_limit(1200); //20 min
        $errors = [];
        $sheetIdx = 1;
        $rowIdx = 2;

        $sheets = Excel::load($file)->get();

        foreach ($sheets as $sheet) {
            foreach ($sheet as $row) {
                if (is_null($row['cedula_asesor'])) {
                    array_push($errors, 'La hoja '.$sheetIdx.' fila '.$rowIdx.' debe tener cédula.');
                }
                $rowIdx++;
            }
            $rowIdx = 2;
            $sheetIdx++;
        }

        return $errors;
    }

    private function storeExcel($file)
    {
        $sheets = Excel::load($file)->get();
        $now = Carbon::now();
        $year = $now->year;
        $measures = Measure::all();
        $months = Calendar::listMonths();

        foreach ($sheets as $sheet) {
            foreach ($sheet as $row) {
                $user = User::where('identification', $row['cedula_asesor'])->first();

                if (!is_null($user)) {
                    foreach ($months as $month) {
                        foreach ($measures as $measure) {
                            if (isset($row[$month.'_'.strtolower($measure->units).
                                '_'.strtolower($this->convertString($measure->name))])) {

                                Sale::updateOrCreate([
                                    'month' => Calendar::getNumberMonth($month),
                                    'year' => $year,
                                    'user_id' => $user->id,
                                    'measure_id' => $measure->id
                                ], [
                                    'value' => $row[$month.'_'.strtolower($measure->units).
                                '_'.strtolower($this->convertString($measure->name))]
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
