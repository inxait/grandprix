<?php

namespace App\Http\Controllers;

use App\City;
use App\Distributor;
use App\User;
use App\Utils\RemoteAddress;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Validator;

class ExcelUsersController extends Controller
{
    public function uploadUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_excel' => 'required|file',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $file = $request->file('users_excel');
        $errors = $this->validateExcel($file);

        if (count($errors)) {
            return back()->withErrors((object) ['excel_errors' => $errors]);
        } else {
            $this->storeExcel($file);

            return back()->with('status', 'Usuarios actualizados correctamente.');
        }
    }

    public function downloadRegisteredSellers()
    {
        $users = User::where('updated_data', true)->orderBy('created_at', 'desc')->get();
        $date = Carbon::now();
        $fileName = $date.'_usuarios_activos';

        return Excel::create($fileName, function($excel) use ($users) {
            $excel->sheet('Usuarios activos', function($sheet) use ($users) {
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

                $sheet->row(1, array(
                    'Distribuidor', 'Cédula asesor', 'Nombres', 'Apellidos',
                    'Género', 'Correo', ' Celular', 'Ciudad', 'Zona', 'Dirección oficina'
                ));

                foreach ($users as $row) {
                    $row->company = (object)['name' => '-'];
                    if (!is_null($row->companies()->first())) {
                        $row->company = $row->companies()->first();
                    }

                    $row->city = '';
                    $city = City::find($row->city_id);

                    if (!is_null($city)) {
                        $row->city = $city->name;
                    }

                    $sheet->appendRow(array(
                        $row->company->name,
                        $row->identification,
                        $row->first_name,
                        $row->last_name,
                        $row->gender,
                        $row->email,
                        $row->cellphone,
                        $row->city,
                        $row->zone,
                        $row->address
                    ));
                }

                $sheet->freezeFirstRow();
            });
        })->export('xlsx');
    }

    public function downloadNotActiveSellers()
    {
        $users = User::where('approved_sent', true)
        ->where('updated_data', false)
        ->orderBy('created_at', 'desc')->get();
        $date = Carbon::now();
        $fileName = $date.'_usuarios_sin_activar';

        return Excel::create($fileName, function($excel) use ($users) {
            $excel->sheet('Usuarios sin activar', function($sheet) use ($users) {
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

                $sheet->row(1, array(
                    'Distribuidor', 'Cédula asesor', 'Nombres', 'Apellidos',
                    'Género', 'Correo', ' Celular', 'Ciudad', 'Zona', 'Dirección oficina'
                ));

                foreach ($users as $row) {
                    $row->company = (object)['name' => '-'];
                    if (!is_null($row->companies()->first())) {
                        $row->company = $row->companies()->first();
                    }

                    $row->city = '';
                    $city = City::find($row->city_id);

                    if (!is_null($city)) {
                        $row->city = $city->name;
                    }

                    $sheet->appendRow(array(
                        $row->company->name,
                        $row->identification,
                        $row->first_name,
                        $row->last_name,
                        $row->gender,
                        $row->email,
                        $row->cellphone,
                        $row->city,
                        $row->zone,
                        $row->address
                    ));
                }

                $sheet->freezeFirstRow();
            });
        })->export('xlsx');
    }

    private function validateExcel($file)
    {
        set_time_limit(1200); //20 min
        $errors = [];
        $index = 2;

        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            if (!isset($row['nit_distribuidor'])) {
                if(isset($row['rol_de_usuario']) && strtolower($row['rol_de_usuario']) !== 'admin'){
                    array_push($errors, "La fila $index debe tener el NIT del distribuidor.");
                    break;
                }
            }

            if (!isset($row['rol_de_usuario'])) {
                array_push($errors, "La fila $index debe rol de usuario.");
                break;
            }

            if (!isset($row['cedula'])) {
                array_push($errors, "La fila $index debe tener la cédula del usuario.");
                break;
            }

            if (!isset($row['nombre'])) {
                array_push($errors, "La fila $index debe tener Nombre.");
                break;
            }

            if (!isset($row['apellidos'])) {
                array_push($errors, "La fila $index debe tener Apellidos.");
                break;
            }

            if (!isset($row['genero'])) {
                if(isset($row['rol_de_usuario']) && strtolower($row['rol_de_usuario']) !== 'distribuidor'){
                    array_push($errors, "La fila $index debe tener Género.");
                    break;
                }
            }

            if (!isset($row['ciudad'])) {
                array_push($errors, "La fila $index debe tener Ciudad.");
                break;
            }

            if (!isset($row['correo_electronico'])) {
                array_push($errors, "La fila $index debe tener Correo Electrónico.");
                break;
            }

            $index++;
        }

        return $errors;
    }

    private function storeExcel($file)
    {
        $index = 2;
        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            if (isset($row['nit_distribuidor']) && !is_null($row['nit_distribuidor'])) {
                $distributor = Distributor::where('nit', trim($row['nit_distribuidor']))->first();

                if (is_null($distributor)) {
                    $distributor = Distributor::create([
                        'name' => trim($row['razon_social_distribuidor']),
                        'nit' => trim($row['nit_distribuidor']),
                    ]);

                    $companyAgile = AgileController::createCompany($distributor);
                    if ($companyAgile != '') {
                        $distributor->company_agile_id = json_decode($companyAgile)->id;
                        $distributor->save();
                    }
                }
            }

            $remoteAddr = new RemoteAddress;
            $city = City::where('name', trim($row['ciudad']))->first();

            $user = User::firstOrCreate([
                'identification' => $row['cedula'],
            ], [
                'first_name' => trim($row['nombre']),
                'last_name' => trim($row['apellidos']),
                'email' => (isset($row['correo_electronico'])) ? trim($row['correo_electronico']) : null,
                'password' => bcrypt(env('TMP_PASS')),
                'gender' => (isset($row['genero'])) ? trim($row['genero']) : null,
                'ip_address' => $remoteAddr->getIpAddress(),
                'cellphone' => (isset($row['celular'])) ? trim($row['celular']) : null,
                'address' => (isset($row['direccion_oficina'])) ? trim($row['direccion_oficina']) : null,
                'zone' => (isset($row['zona'])) ? trim($row['zona']) : null,
                'city_id' => (isset($city->id)) ? $city->id : null
            ]);

            if (isset($distributor)) {
                $user->companies()->attach($distributor);

                if (!count($user->roles()->get())) {
                    $user->assignRole($this->getRoleNameToStore(strtolower($row['rol_de_usuario'])));
                }
            }else{
                $user->assignRole($this->getRoleNameToStore(strtolower($row['rol_de_usuario'])));
            }
        }
    }

    /**
     *
     * Obtiene el rol correspondiente al usuario.
     * @param string $name
     * @return string
     *
     */
    private function getRoleNameToStore($name)
    {
        switch ($name) {
            case 'distribuidor':
                return 'dealer';
                break;
            case 'admin':
                return 'admin';
                break;
            case 'asesor':
                return 'seller';
                break;
            default:
                return 'seller';
                break;
        }
    }
}
