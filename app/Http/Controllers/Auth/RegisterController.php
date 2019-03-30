<?php

namespace App\Http\Controllers\Auth;

use App\Department;
use App\Distributor;
use App\User;
use App\Http\Controllers\Controller;
use App\Utils\RemoteAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'inicio';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $departments = Department::all();
        $distributors = Distributor::all();

        return view('pages.register')->with(compact('departments', 'distributors'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:90',
            'last_name' => 'required|string|max:90',
            'distributor' => 'required|integer',
            'identification' => 'required|string|max:30|unique:users',
            'email' => 'required|string|email|max:255',
            'cellphone' => 'required|string|max:20',
            'address' => 'string|max:255',
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ]);
        }

        $distributor = Distributor::find($request->distributor);

        if (!is_null($distributor)) {
            $remoteAddr = new RemoteAddress;
            $user = $this->create($request->all());
            $user->ip_address = $remoteAddr->getIpAddress();
            $user->save();
            $user->assignRole('seller');
            $user->companies()->attach($distributor);

            return response()->json(['success' => true]);
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['No se encontró el distribuidor']
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'identification' => $data['identification'],
            'email' => $data['email'],
            'cellphone' => $data['cellphone'],
            'password' => bcrypt(env('TMP_PASS')),
            'zone' => $data['zone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'city_id' => $data['city']
        ]);
    }
}
