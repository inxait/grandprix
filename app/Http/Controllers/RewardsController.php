<?php

namespace App\Http\Controllers;

use App\Measure;
use App\Reward;
use Illuminate\Http\Request;
use Image;
use Storage;
use Validator;

class RewardsController extends Controller
{
    private $baseRules = [
        'title' => 'required|max:80',
    ];

    public function index()
    {
        $rewards = Reward::orderBy('created_at', 'DESC')->paginate(10);

        return view('pages.admin.list-rewards')->with(compact('rewards'));
    }

    public function list()
    {
        $rewards = Reward::where('active', true)->get();

        return response()->json(['success' => true, 'info' => $rewards]);
    }

    public function showMainRewards()
    {
        $measures = Measure::all();
        $data['trimester'] = Reward::where('active', true)->where('type', 'trimestral')->first();
        $data['montly'] = Reward::where('active', true)->where('type', 'mensual')->first();

        foreach ($measures as $measure) {
            $data[studly_case($measure->name)] = Reward::where('active', true)
            ->where('type', mb_strtolower($measure->name))->first();
        }

        return view('pages.rewards')->with(compact('data', 'measures'));
    }

    public function showCreate()
    {
        $measures = Measure::all();

        return view('pages.admin.create-reward')->with(compact('measures'));
    }

    public function store(Request $request)
    {
        $this->baseRules['image'] = 'required|image';

        $validator = Validator::make($request->all(), $this->baseRules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $imgPath = $request->image->store('uploads/premios');

        if ($request->hasFile('legal')) {
            $legalPath = $request->legal->store('uploads/premios');
        }

        Reward::create([
            'title' => $request->input('title'),
            'image' => $imgPath,
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'legal' => (isset($legalPath)) ? $legalPath : null
        ]);

        return redirect('dashboard/premios')->with('status', 'Se ha creado el premio correctamente.');
    }

    /**
     * Muestra el formulario para editar un premio
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reward = Reward::find($id);
        $measures = Measure::all();

        return view('pages.admin.edit-reward', compact('reward', 'measures'));
    }

    /**
     * Actualiza el premio especificado en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->hasFile('image')) {
            $this->baseRules['image'] = 'required|image';
        }

        if ($request->hasFile('legal')) {
            $this->baseRules['legal'] = 'required|file';
        }

        $validator = Validator::make($request->all(), $this->baseRules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $reward = Reward::find($id);

        $toUpdate = [
            'title' => $request->title,
            'type' => $request->type,
            'description' => $request->description
        ];

        if ($request->hasFile('image')) {
            if (file_exists(storage_path('app/public/'.$reward->image))) {
                unlink(storage_path('app/public/'.$reward->image));
            }
            $imgPath = $request->image->store('uploads/premios');
            $toUpdate['image'] = $imgPath;
        }

        if ($request->hasFile('legal')) {
            if (file_exists(storage_path('app/public/'.$reward->legal))) {
                unlink(storage_path('app/public/'.$reward->legal));
            }
            $legalPath = $request->legal->store('uploads/premios');
            $toUpdate['legal'] = $legalPath;
        }

        $reward->update($toUpdate);

        return redirect('dashboard/premios')->with('status', 'Se ha editado el premio correctamente.');
    }

    /**
    * Cambia el estado de un premio.
    * @param $id
    * @return \Illuminate\Http\Response
    */
    public function state($id){
        if(isset($id)){
            //Consulta la informacion de un premio.
            $re = Reward::where('id', $id)->first();
            $re->active = !$re->active;
            $re->save();
        }
        $estado = ($re->active != 0) ? 'Activado' : 'Desactivado';
        //Devuelve mensaje informativo.
        return redirect('dashboard/premios')->with('status', 'El Premio ' . $re->title . ' ha sido ' . $estado);
    }
}
