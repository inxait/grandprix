<?php

namespace App\Http\Controllers;

use App\Fulfillment;
use App\Measure;
use App\User;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Validator;

class ExcelMetricsController extends Controller
{
    public function uploadFulfillment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fulfillments_excel' => 'required|file'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $month = $request->input('month');
        $file = $request->file('fulfillments_excel');
        $errors = $this->validateExcel($file);

        if (count($errors)) {
            return back()->withErrors((object) ['fulfillments_excel' => $errors]);
        } else {
            $this->storeExcel($file, $month);

            return back()->with('status', 'Cumplimientos actualizados correctamente.');
        }
    }

    private function validateExcel($file)
    {
        set_time_limit(1200); //20 min
        $errors = [];
        $index = 2;

        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            if (is_null($row['cedula_asesor'])) {
                array_push($errors, 'La fila '.$index.' debe tener cédula.');
            }
            $index++;
        }

        return $errors;
    }

    private function storeExcel($file, $month)
    {
        $rows = Excel::load($file)->get();
        $measures = Measure::all();
        $now = Carbon::now();

        foreach ($rows as $row) {
            $user = User::where('identification', trim($row['cedula_asesor']))->first();

            if (count($user)) {
                Fulfillment::where('user_id', $user->id)->update(['active' => false]);

                foreach ($measures as $measure) {
                    $name = strtolower($this->convertString($measure->name));

                    if (isset($row['meta_'.$measure->units.'_'.$name])) {
                        if (!is_null($row['meta_'.$measure->units.'_'.$name])) {
                            $fulfillment = Fulfillment::create([
                                'title' => 'Cumplimiento x '.$name,
                                'goal' =>$row['meta_'.$measure->units.'_'.$name],
                                'reward' => $row['recompensa_cumplimiento_'.
                                                  $measure->units.'_'.$name],
                                'overcompliance_value' => $row['sobrecumplimiento_'.
                                                               $measure->units.'_'.$name],
                                'overcompliance_reward' => $row['recompensa_sobrecumplimiento_'.
                                                                $measure->units.'_'.$name],
                                'period' => 'Mensual',
                                'month' => $month,
                                'year' => $now->year,
                                'user_id' => $user->id,
                                'measure_id' => $measure->id,
                            ]);
                        }
                    }
                }
            }
        }
    }
}
