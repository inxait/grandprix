<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Post;
use App\Distributor;
use App\Point;
use App\User;
use App\Report;
use Auth;
use DB;
use Excel;
use Carbon\Carbon;
USE App\Document;

class DealerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * Carga el home del rol concesionario.
     * @return Illuminate\Support\Facades\View
     *
     */
    public function index(Request $request)
    {
        $identification = Auth::user()->identification;
        $distributor = Distributor::where(['nit' => $identification])->first();

        $users = [];
        $search = $request->get('search');
        if(!is_null($distributor)){
            $users = self::getUsersDealer($distributor->id,$search);
        }

        return view('pages.dealer.index', compact('users', 'distributor'));
    }

    /**
     *
     * Carga la sección de noticias.
     * @return Illuminate\Support\Facades\View
     *
     */
    public function showNews()
    {
        $news = Post::where('published', true)->orderBy('created_at', 'DESC')->get();
        return view('pages.news')->with(compact('news'));
    }

    /**
     *
     * Carga el detalle de la noticia.
     * @return Illuminate\Support\Facades\View
     *
     */
    public function showDetailNews($slug)
    {
        $news = Post::where('published', true)->where('slug', $slug)->first();
        return view('pages.news-detail')->with(compact('news'));
    }

    /**
     *
     * Obtiene el material de estudio registrado.
     * @return Illuminate\Support\Facades\View
     *
     */
    public function getStudyMaterial()
    {
        $roles = Auth::user()->getRoleNames();
        $role = Role::where('name', $roles[0])->first();

        $documents = [];
        $docs = DB::table('documents_role')->where('role_id', $role->id)->get();

        foreach ($docs as $item) {
            $document = Document::find($item->document_id);
            array_push($documents, $document);
        }

        return view('pages.study-material')->with(compact('documents'));
    }

    /**
     *
     * Obtiene los usuarios del concesionario.
     * @param int $dealer
     * @return array
     *
     */
    private static function getUsersDealer($dealer,$search){
      if (!empty($search)) {
        $users = User::join('distributor_user','distributor_user.user_id','=','users.id')
        ->where('distributor_user.distributor_id',$dealer)
        ->where('users.first_name', 'like', '%' . $search . '%')
        ->orWhere('users.last_name', 'like', '%' . $search . '%')
        ->orWhere('users.identification', 'like', '%' . $search . '%')
        ->paginate(10);
      }else{
        $users = User::join('distributor_user','distributor_user.user_id','=','users.id')
        ->where('distributor_user.distributor_id',$dealer)
        ->paginate(10);
      }

      return $users;
    }

    /**
     *
     * Obtiene el resumen de puntos por usuario.
     * @param int $id
     *
     */
    public function getPointsUser($id)
    {
        $user = User::find($id);
        $points = Point::where('user_id',$id)->get();

        return view('pages.dealer.list-points-user')->with(compact('user','points'));
    }


    public function download()
    {
      $identification = Auth::user()->identification;
      $distributor = Distributor::where(['nit' => $identification])->first();

      $users = [];
      if(!is_null($distributor)){
        $users = User::join('distributor_user','distributor_user.user_id','=','users.id')
        ->where('distributor_user.distributor_id',$distributor->id)
        ->paginate(10);
      }

      $date = Carbon::now();
      $fileName = $date.'_usuarios';

      return Excel::create($fileName, function($excel) use ($users) {
          $excel->sheet('Usuarios', function($sheet) use ($users) {
              $sheet->setStyle(array(
                  'font' => array(
                      'name' =>  'Arial',
                      'size' =>  11,
                  )
              ));

              $sheet->cells('A1:J1', function($cells) {
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });

              $sheet->row(1, array('Nombre', 'Identificación', 'Correo', ' Celular', 'Puntaje'));

              foreach ($users as $row) {
                  $sheet->appendRow(array(
                      $row->first_name." ".$row->last_name,
                      $row->identification,
                      $row->email,
                      $row->cellphone,
                      $row->getPoints($row->user_id)
                  ));
              }

              $sheet->freezeFirstRow();
          });
      })->export('xlsx');
    }


    public function report()
    {
      $users = Report::getUsers();

      $now = date('Y-m-d H:i:s');
      $fileName = $now.'_'.'puntos_usuarios_etapa_actual';

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
              $sheet->appendRow(array(
                $user->identification,
                $user->first_name." ".$user->last_name,
                (isset(Report::totalType($user->id,'trivia')->total) ? Report::totalType($user->id,'trivia')->total : '0'),
                (isset(Report::totalType($user->id,'other')->total) ? Report::totalType($user->id,'other')->total : '0'),
                (isset(Report::totalType($user->id,'update')->total) ? Report::totalType($user->id,'update')->total : '0'),
                (isset(Report::totalType($user->id,'all')->total) ? Report::totalType($user->id,'all')->total : '0')
              ));
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
                $liquidations = $user->liquidations;
                foreach ($liquidations as $liquid) {
                    array_push($liquidations_users, $liquid);
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
