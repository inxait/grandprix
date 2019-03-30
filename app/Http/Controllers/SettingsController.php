<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Validator;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return view('pages.admin.list-settings')->with(compact('settings'));
    }

    public function showSetting($id)
    {
        $setting = Setting::find($id);

        return view('pages.admin.edit-setting')->with(compact('setting'));
    }

    public function updateSetting(Request $request)
    {
        $rules = [];

        if ($request->input('type') == 1) {
            $rules['value'] = 'required|url';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $setting = Setting::find($request->input('id'));
        $setting->value = $request->input('value');
        $setting->save();

        return redirect('dashboard/configuraciones');
    }
}
