<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Document;
use Spatie\Permission\Models\Role;
use Validator;
use DB;

class DocumentsController extends Controller
{

    /**
     *
     * Muestra el listado de documentos creados.
     *
     */
    public function list()
    {
        $documents = Document::paginate(10);
        return view('pages.admin.documents.list', compact('documents'));
    }

    /**
     *
     * Carga el formulario de creacion de documento.
     *
     */
    public function showCreate()
    {
        $roles = Role::where('name', '!=', 'admin')->where('name', '!=', 'super-admin')->get();
        return view('pages.admin.documents.create', compact('roles'));
    }

    /**
     *
     * Valida y guarda la informacion del documento a crear.
     * @param Request $request
     *
     */
    public function store(Request $request)
    {

        $messages = [
            'name.required' => 'Campo nombre es obligatorio.',
            'name.max' => 'El campo nombre acepta máximo 60 caracteres.',
            'file.required' => 'Campo archivo es obligatorio.',
            'file.mimes' => 'La extensión del archivo no esta permitida.',
            'file.size' => 'El tamaño permitido para los archivos es de 2MB.',
            'role_id.required' => 'Campo rol es obligatorio.'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'file' => 'required|mimes:jpg,jpeg,gif,png,xls,xlsx,doc,docx,pdf,mp4,webm,ogg|max:10000',
            'role_id' => 'required|integer'
        ],$messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');

        $uploadPath = $file->store('uploads');
        $extention = $file->getClientOriginalExtension();
        $type_doc = $this->getDocumentLogo($extention);

        $document = Document::create([
            'name' => $request->name,
            'url' => $uploadPath,
            'type' => $type_doc['type'],
            'logo' => $type_doc['logo']
        ]);

        $roles = Role::where('name', '!=', 'admin')->where('name', '!=', 'super-admin')->get();

        if ($request->role_id == 0)  {
            foreach ($roles as $role) {
                DB::table('documents_role')->insert([
                    'document_id' => $document->id,
                    'role_id' => $role->id
                ]);
            }
        } else {
            DB::table('documents_role')->insert([
                'document_id' => $document->id,
                'role_id' => $request->role_id
            ]);
        }

        return redirect('dashboard/material')->with('status', 'Se ha guardado el archivo correctamente.');
    }

    /**
     *
     * Elimna el documento.
     * @param int $id
     *
     */
    public function delete($id)
    {
        $document = Document::find($id);

        if (file_exists(storage_path('app/public/'.$document->url))) {
            unlink(storage_path('app/public/'.$document->url));
        }

        DB::table('documents_role')->where([
            'document_id' => $id,
        ])->delete();

        $document->delete();

        return redirect('dashboard/material')->with('status', 'Se ha eliminado el archivo correctamente');
    }
    /**
     *
     * Devuelve el logo del tipo de documento escogido.
     * @param string $ext
     *
     */
    private function getDocumentLogo($ext)
    {
        $excel = ['xls', 'xlsx'];
        $doc = ['doc','docx'];
        $image = ['jpg','jpeg','gif','png'];
        $video = ['mp4','webm','ogg'];

        if(in_array($ext, $excel)){
            return ['logo'=>'images/excel.png', 'type' => 'Excel'];
        }

        if(in_array($ext, $doc)){
            return ['logo'=>'images/word.png', 'type' => 'Documento'];
        }

        if(in_array($ext, $image)){
            return ['logo'=>'images/image.png', 'type' => 'Imagen'];
        }

        if(in_array($ext, $video)){
            return ['logo'=>'images/video.png', 'type' => 'Video'];
        }

        if($ext === 'pdf'){
            return ['logo'=>'images/pdf.png', 'type' => 'Pdf'];
        }
    }
}
