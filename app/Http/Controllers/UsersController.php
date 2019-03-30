<?php

namespace App\Http\Controllers;

use App\Avatar;
use App\City;
use App\AccessLog;
use App\TriviaUser;
use App\Department;
use App\LiquidationUser;
use App\Point;
use App\Liquidation;
use App\Measure;
use App\Utils\RandomStringGenerator;
use App\User;
use App\Helpers\Points;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class UsersController extends Controller
{
    public function index() {
        $usersNotApproved = User::where('approved_sent', false)
        ->orderBy('created_at', 'desc')->paginate(10);
        $usersNotActivated = User::where('approved_sent', true)
        ->where('updated_data', false)
        ->orderBy('created_at', 'desc')->paginate(10);
        $usersRegistered = User::where('updated_data', true)
        ->orderBy('created_at', 'desc')->paginate(10);

        return view('pages.admin.list-users', [
            'notApproved' => $usersNotApproved,
            'notActivated' => $usersNotActivated,
            'registered' => $usersRegistered,
        ]);
    }

    public function approveAllUsersRegistration() {
        $users = User::where('approved_sent', false)->get();

        foreach ($users as $user) {
            $this->sendApprovedEmail($user);
        }

        return back()->with('status', 'Se aprobó el registro de todos los usuarios correctamente');
    }

    public function approveUserRegistration($id) {
        $user = User::find($id);
        $this->sendApprovedEmail($user);

        return back()->with('status', 'Se ha aprobado el registro del usuario correctamente');
    }

    private function sendApprovedEmail($user) {
        //$passGen = new RandomStringGenerator;
        //$password = $passGen->generate(8);
        $password = $user->identification;

        $seller['name'] = $user->first_name;
        $seller['identification'] = $user->identification;
        $seller['password'] = $password;

        $template = view('emails.activate-account', $seller)->render();

        $data['user_email'] = $user->email;
        $data['user_name'] = $seller['name'];
        //$data['email_subject'] = 'Bienvenido a Grand Prix ACDelco, gana tus primeros km ahora';
        $data['email_subject'] = $seller['name'].', estás a un paso de completar tu registro. ¡ACTÍVATE AHORA!';
        $data['email_description'] = 'Activación platafoma Grand Prix ACDelco';
        $data['email_template'] = $template;

        if (MailjetController::sendEmail($data)) {
            $user->approved_sent = true;
            $user->password = bcrypt($password);
            $user->save();
        }
    }

    public function showActivateStep1() {
        return view('pages.account-activation-step1');
    }

    public function updateProfileStep1(Request $request) {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|string|max:90',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();
        $user->surname = $request->input('surname');
        $user->save();

        Avatar::updateOrCreate(['user_id' => $user->id], [
            'helmet' => $request->input('helmet_color'),
            'uniform' => $request->input('uniform_color'),
            'gloves' => $request->input('gloves_color'),
            'shoes' => $request->input('shoes_color')
        ]);

        return redirect('activarse-paso-2');
    }

    public function showActivateStep2() {
        $user = Auth::user();
        $avatar = $user->avatar()->first();

        return view('pages.account-activation-step2')->with(compact('avatar'));
    }

    public function updateProfileStep2(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();
        $user->password = bcrypt($request->input('password'));
        $user->updated_data = true;
        $user->data_update_date = Carbon::now();
        $user->save();

        $now = Carbon::now();

        $points = Point::create([
            'points_event' => 'Activación en la plataforma',
            'month' => $now->month,
            'year' => $now->year,
            'value' => 10,
            'type' => 1,
            'user_id' => $user->id
        ]);

        $company = $user->companies()->first();
        $city = City::find($user->city_id);
        $user->city_name = $city->name;
        $user->department = $city->department()->first()->name;
        $user->company = $user->companies()->first()->name;
        $user->points = (Points::getUserPointsTotal($user->id) == 0) ? 10 : Points::getUserPointsTotal($user->id);

        /*$agileUser = AgileController::createCustomer($user);
        unset($user->city_name);
        unset($user->department);
        unset($user->company);
        unset($user->points);

        $user->agile_id = json_decode($agileUser)->id;
        $user->save();*/

        return redirect('inicio');
    }

    public function showEditAccount() {
        $departments = Department::all();
        $user = Auth::user();
        $city = null;

        if (!is_null($user->city_id)) {
            $city = City::find($user->city_id);
            $city->department = $city->department()->first();
        }

        if ($user->hasRole('seller') || $user->hasRole('dealer')) {
            return view('pages.edit-profile')->with(compact('user', 'departments', 'city'));
        }

        return view('pages.admin.edit-account')->with(compact('user', 'departments', 'city'));
    }

    public function updateAccount(Request $request) {
        $rules = [
            'first_name' => 'required|string|max:90',
            'last_name' => 'required|string|max:90',
            'surname' => 'required|string|max:90',
            'email' => 'required|email|max:250',
            'city' => 'required|integer',
            'address' => 'required|string|max:254',
        ];

        if ($request->input('password') != '') {
            $rules['password'] = 'required|confirmed|min:6';
        }

        if ($request->input('zone') != '') {
            $rules['zone'] = 'string|max:80';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::find($request->input('id'));
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->surname = $request->input('surname');
        $user->email = $request->input('email');
        $user->address = $request->input('address');
        $user->city_id = $request->input('city');
        $user->updated_data = true;

        if ($request->input('zone') != '') {
            $user->zone = $request->input('zone');
        }

        if ($request->input('password') != '') {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        if ($user->hasRole('seller')) {
            $company = $user->companies()->first();
            $city = City::find($user->city_id);
            $user->city_name = $city->name;
            $user->department = $city->department()->first()->name;
            $user->company = $user->companies()->first()->name;

            //AgileController::updateCustomer($user);
        }

        if ($user->hasRole('seller')) {
            return redirect('/inicio')->with('status', 'Cuenta actualizada correctamente.');
        }

        if ($user->hasRole('dealer')) {
            return redirect('/dealer/inicio')->with('status', 'Cuenta actualizada correctamente.');
        }

        return redirect('/dashboard')->with('status', 'Cuenta actualizada correctamente.');
    }

    public function showProfile() {
        $user = Auth::user();
        $points = Point::where('user_id', $user->id)->get();
        $liquidations = [];
        $allLiquidations = LiquidationUser::where('user_id', $user->id)->get();

        foreach ($allLiquidations as $item) {
            $liq = Liquidation::find($item->liquidation_id);
            $measure = Measure::find($liq->measure_id);

            $liquidations[$item->liquidation_id] = $item;
            $liquidations[$item->liquidation_id]['measure'] = $measure->name;
        }

        return view('pages.profile')->with(compact('points', 'liquidations'));
    }

    public function delete($id) {
        $user = User::find($id);

        if (!is_null($user)) {
            if ($user->updated_data) {
                Avatar::where('user_id', $user->id)->delete();
                Point::where('user_id', $user->id)->delete();
                LiquidationUser::where('user_id', $user->id)->delete();
            }

            AccessLog::where('user_id',$user->id)->delete();
            TriviaUser::where('user_id',$user->id)->delete();
            DB::table('distributor_user')->where('user_id',$user->id)->delete();

            SalesController::deleteUserSales($user->id);
            FulfillmentsController::deleteUserFulfillments($user->id);

            $user->companies()->detach();
            $user->roles()->detach();
            $user->delete();

            return back()->with('status', 'Se ha eliminado el usuario correctamente');
        } else {
            return back()->withErrors(['No se encontró el usuario a eliminar.']);
        }
    }

    /**
     * Muestra el listado de usuarios no aprobados.
     * @param Request $request
     * @return \Illuminate\View\View | \Illuminate\Contracts\View\Factory
     */
    public function notApproved(Request $request) {
        $usersNotApproved = User::word($request->get('palabra'))->where('approved_sent', false)
        ->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.admin.not-approved-users', [
            'notApproved' => $usersNotApproved
        ]);
    }

    /**
     * Muestra el listado de usuarios no activados.
     * @param Request $request
     * @return \Illuminate\View\View | \Illuminate\Contracts\View\Factory
     */
    public function notActivated(Request $request) {
        $usersNotActivated = User::word($request->get('palabra'))->where('approved_sent', true)
        ->where('updated_data', false)
        ->orderBy('created_at', 'desc')->paginate(10);
        //Devuelve la vista.
        return view('pages.admin.not-activated-users', [
            'notActivated' => $usersNotActivated,
        ]);
    }

    /**
     * Muestra el listado de usuarios registrados.
     * @param Request $request
     * @return \Illuminate\View\View | \Illuminate\Contracts\View\Factory
     */
    public function usersRegistered(Request $request) {

        $usersRegistered = User::word($request->get('palabra'))->where('updated_data', true)->orderBy('created_at', 'desc')->paginate(10);
        return view('pages.admin.users-registered', [
            'registered' => $usersRegistered,
        ]);
    }

    /**
     * Encargado de validar y actualizar la informacion de usuario enviada desde el formulario de edicion.
     * @param Request $request
     * @param int $id
     * @param int $tipo
     * @return \Symfony\Component\HttpFoundation\Response | \Illuminate\Contracts\Routing\ResponseFactory
     */
    public function updateUser(Request $request, $id,$tipo) {
        //Variable arreglo
        $rules = [
            'first_name' => 'required|string|max:90',
            'last_name' => 'required|string|max:90',
            'email' => 'required|string|email|max:255',
            'gender' => 'required|string',
        ];
        //Consulta el cliente por identificacion.
        $user_exists = User::where('identification', $request->identification)->first();
        //Verifica que el usuario existe para no validar su documento.
        if ($user_exists) {
            if ($user_exists->id == $id) {
                $rules['identification'] = 'required|string|max:30';
            } else {
                $rules['identification'] = 'required|string|max:30|unique:users';
            }
        } else {
            $rules['identification'] = 'required|string|max:30';
        }
        //Valida los datos enviados del formulario y las reglas
        $validator = Validator::make($request->all(), $rules);
        //Valida si existen errores de formulario.
        if ($validator->fails()) {
            //Devuelve respuesta y errores.
            return back()->withErrors($validator)->withInput();
        }
        //Obtiene la informacion del usuario.
        $user = User::where('id', $id)->first();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->identification = $request->identification;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->save();

        switch ($tipo) {
            case 'reg':
            //Devuelve al listado predeterminado
            return redirect('dashboard/registrados');
            break;
            case 'not':
            //Devuelve al listado predeterminado
            return redirect('dashboard/inactivos');
            break;
            default:
            //Devuelve al listado predeterminado
            return redirect('dashboard/usuarios');
            break;
        }
    }

    /**
     * Carga la informacion en la vista que contendra el formulario de edición de usuario.
     * @param int $id
     * @param int $tipo
     * @return \Illuminate\Support\Facades\View
     */
    public function showUpdateUser($id,$tipo) {
        $user = User::where('id', $id)->first();
        return view('pages.admin.user-update')->with('data', ['user' => $user,'tipo'=>$tipo]);
    }
}
