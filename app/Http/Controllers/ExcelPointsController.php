<?php

namespace App\Http\Controllers;

use App\Helpers\Calendar;
use App\Point;
use App\User;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Validator;

class ExcelPointsController extends Controller
{
    public function uploadPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'points_excel' => 'required|file',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('points_excel');
        $errors = $this->validateExcel($file);

        if (count($errors)) {
            return back()->withErrors((object) ['excel_errors' => $errors]);
        } else {
            $this->storeExcel($file);

            return back()->with('status', 'Puntos actualizados correctamente.');
        }
    }

    private function validateExcel($file)
    {
        set_time_limit(1200); //20 min
        $errors = [];
        $index = 2;

        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            if (!isset($row['cedula_asesor']) ||is_null($row['cedula_asesor'])) {
                array_push($errors, 'La fila '.$index.' debe tener cédula.');
            }

            if (!isset($row['mes']) || is_null($row['mes'])) {
                array_push($errors, 'La fila '.$index.' debe tener el mes de la actualización de puntos.');
            }

            if (!isset($row['nombre_evento_puntos']) || is_null($row['nombre_evento_puntos'])) {
                array_push($errors, 'La fila '.$index.' debe tener el nombre de la actualización de puntos.');
            }

            if (!isset($row['valor']) || is_null($row['valor'])) {
                array_push($errors, 'La fila '.$index.' debe tener el valor positivo o negativo de puntos.');
            }

            if (isset($row['mes']) && !is_null($row['mes'])) {
                $months = Calendar::listMonths();
                if (!in_array(strtolower($row['mes']), $months)) {
                    array_push($errors, 'La fila '.$index.' debe tener un mes válido.');
                }
            }

            $index++;
        }

        return $errors;
    }

    private function storeExcel($file)
    {
        $rows = Excel::load($file)->get();
        $now = Carbon::now();

        foreach ($rows as $row) {
            $seller = User::where('identification', $row['cedula_asesor'])->first();

            if (count($seller)) {
                Point::create([
                    'points_event' => $row['nombre_evento_puntos'],
                    'type' => 5,
                    'month' => Calendar::getNumberMonth(strtolower($row['mes'])),
                    'year' => $now->year,
                    'value' => (int) $row['valor'],
                    'user_id' => $seller->id
                ]);

                AgileController::updateCustomerScore($seller);
            }
        }
    }
}
