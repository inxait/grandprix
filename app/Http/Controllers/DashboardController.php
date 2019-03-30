<?php

namespace App\Http\Controllers;

use App\City;
use App\Department;
use App\User;
use App\Report;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Points;
use Excel;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalUsers = DB::select('select count(*) as total from users');
        $totalRegisteredUsers = DB::select('select count(*) as total from users where updated_data = 1');
        $totalActivePendingUsers = DB::select('select count(*) as total from users where updated_data = 0');
        $ranking = Points::getUsersGeneralRanking();

        return view('pages.admin.dashboard')->with(compact('totalUsers', 'totalRegisteredUsers', 'totalActivePendingUsers', 'ranking'));
    }

    public function export()
    {
        $users = User::where('updated_data', true)->whereNull('agile_id')->get();
        $date = Carbon::now();
        $fileName = $date.'_users';

        return Excel::create($fileName, function($excel) use ($users) {
            $excel->sheet('Empresarios', function($sheet) use ($users) {
                $sheet->row(1, array(
                    'First Name', 'Last Name', 'Title', 'Company', 'Email(default)', 'Phone(default)',
                    'Address', 'City', 'State', 'Country', 'Created Date'
                ));

                foreach ($users as $row) {
                    $city = City::find($row->city_id);
                    $company = $row->companies()->first();

                    if (count($company)) {
                        $sheet->appendRow(array(
                            $row->first_name,
                            $row->last_name,
                            'Asesor de ventas',
                            $company->name,
                            $row->email,
                            $row->cellphone,
                            $row->address,
                            $city->name,
                            Department::find($city->department_id)->name,
                            'CO',
                            '03/16/2018'
                        ));
                    }
                }
            });
        })->export('xlsx');
    }

    /**
     *
     * Descarga el reporte completo de usuarios, totales y liquidaciones.
     *
     */
    public function downloadReport()
    {
      $users = User::get();

      $now = date('Y-m-d H:i:s');
      $fileName = $now.'_'.'puntos_usuarios';

      return Excel::create($fileName, function($excel) use ($users) {
        $excel->sheet('Usuarios', function ($sheet) use ($users) {

              // ####################### render headers ########################

              // header 1
          $items = array('', '', 'Actualizacion de datos');
          $trivias_get = DB::table('trivias')->select(DB::raw('CONCAT("Trivia: ",start_date," ",finish_date) as name_concat'),'id','name')->get();

          foreach ($trivias_get as $value) {
            array_push($items,$value->name_concat,$value->name_concat,$value->name_concat);
        }

        $sheet->row(1,$items);

              // header 2
        $items2 = array('Numero Documento', 'Nombre Completo','Kms');
        foreach ($trivias_get as $value) {
            array_push($items2,'Kms','Tiempo','Participo');
        }

        $sheet->row(2, $items2);

              // ################### end render headers ########################

        $sheet = Report::setStyles($sheet);

        foreach ($users as $user) {
            if($user->hasRole('seller')){
                $render = array(
                    $user->identification,
                    $user->first_name." ".$user->last_name,
                    Report::pointsUpdateProfile($user->id)
                );
                foreach ($trivias_get as $value) {
                    $one = (isset(Report::pointsTrivia($user->id,$value->name)->value) ? Report::pointsTrivia($user->id,$value->name)->value : '0');
                    $two = (isset(Report::timeTrivia($user->id,$value->id)->time_to_respond) ? Report::timeTrivia($user->id,$value->id)->time_to_respond : '0');
                    $three = (isset(Report::pointsTrivia($user->id,$value->name)->value) ? 'SI' : 'NO');
                    array_push($render,$one,$two,$three);
                }
                $sheet->appendRow($render);
            }
        }
    });

        $excel->sheet('Totales', function ($sheet) use ($users) {
            $sheet->mergeCells('A1:H1');

            $sheet->row(1,array('Totales'));
            $sheet->row(2,array('Numero Documento', 'Nombre Completo','Trivia','Otros','ActuaLizacion de datos','Total'));

            $sheet->cells('A1:H2', function($cells) {
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
            });

            foreach ($users as $user) {
                if($user->hasRole('seller')){
                    $sheet->appendRow(array(
                        $user->identification,
                        $user->first_name." ".$user->last_name,
                        (isset(Report::totalType($user->id,'trivia')->total) ? Report::totalType($user->id,'trivia')->total : '0'),
                        (isset(Report::totalType($user->id,'other')->total) ? Report::totalType($user->id,'other')->total : '0'),
                        (isset(Report::totalType($user->id,'update')->total) ? Report::totalType($user->id,'update')->total : '0'),
                        (isset(Report::totalType($user->id,'all')->total) ? Report::totalType($user->id,'all')->total : '0')
                    ));
                }
            }

        });

        $excel->sheet('Liquidaciones', function($sheet) use ($users){
            $sheet->row(1, array('Liquidación', 'Nombre Completo', 'Documento', 'Referencia', 'Porcentaje Actual', 'Meta', 'Valor Actual', 'Ventas Totales', 'Total Recibido', 'Porcentaje a Dar', 'Valor Unidad', 'Fecha de Generación'));

            $sheet->cells('A1:L1', function($cells) {
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
            });

            $liquidations_users = [];
            foreach ($users as $user) {
                if($user->hasRole('seller')){
                    $liquidations = $user->liquidations;
                    foreach ($liquidations as $liquid) {
                        array_push($liquidations_users, $liquid);
                    }
                }
            }

            foreach($liquidations_users as $item){
                $history = json_decode($item->pivot->history);

                $sheet->appendRow([
                    $item->name,
                    $history->user_first_name.' '.$history->user_last_name,
                    $history->user_identification,
                    $history->reference,
                    $history->current_percent,
                    $history->goal,
                    $history->current_value,
                    $history->total_sales,
                    $history->receive_total,
                    $history->percent_to_give,
                    $history->unit_val,
                    $item->pivot->created_at
                ]);
            }

        });

    })->export('xlsx');
}
}
