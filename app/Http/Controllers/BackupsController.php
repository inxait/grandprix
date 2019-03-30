<?php

namespace App\Http\Controllers;

use Artisan;
use Illuminate\Http\Request;
use Storage;

class BackupsController extends Controller
{
    public function showBackups()
    {
        $files = scandir(storage_path('app/'.config('app.name')));
        $backups = array_diff($files, array('.', '..'));

        return view('pages.admin.list-backups')->with(compact('backups'));
    }

    public function create()
    {
        Artisan::call('backup:run');

        return redirect('dashboard/backups');
    }

    public function delete($name)
    {
        unlink(storage_path('app/'.config('app.name').'/'.$name));

        return redirect('dashboard/backups')->with('status', 'Se ha eliminado el backup correctamente.');
    }

    public function download($name)
    {
        return response()->download(storage_path('app/'.config('app.name').'/'.$name));
    }
}
