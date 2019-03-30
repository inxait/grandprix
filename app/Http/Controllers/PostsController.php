<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use Illuminate\Http\Request;
use Image;
use Storage;
use Validator;

class PostsController extends Controller
{
    private $baseRules = [
      'title' => 'required|max:255',
      'excerpt' => 'required|max:300',
      'description' => 'required',
    ];

    public function showNews()
    {
        $news = Post::orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();

        return view('pages.admin.list-news')->with(compact('news', 'categories'));
    }

    public function showCreateNews()
    {
        $categories = Category::all();
        return view('pages.admin.create-news')->with(compact('categories'));
    }

    public function createNews(Request $request)
    {
        $this->baseRules['image'] = 'required|file';
        //related material
        if ($request->hasFile('related_material')) {
            $this->baseRules['related_material'] = 'required|file';
        }

        $validator = Validator::make($request->all(), $this->baseRules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('image');
        $uploadPath = $file->store('uploads');

        $data = array(
          'title' => $request->input('title'),
          'excerpt' => $request->input('excerpt'),
          'image' => $uploadPath,
          'slug' => str_slug($request->input('title'), '-'),
          'description' => $request->input('description')
        );

        if ($request->hasFile('related_material')) {
            $file_material = $request->file('related_material');
            $uploadPathMaterial = $file_material->store('uploads');
            $type = Post::typeRelatedMaterial($file_material);
            $data['related_material'] = $uploadPathMaterial;
            $data['type_related_material'] = $type;
        }

        $post = Post::create($data);

        $categoryIds = $request->input('categories');

        if (count($categoryIds)) {
            foreach ($categoryIds as $id) {
                $category = Category::find($id);
                $post->categories()->attach($category);
            }
        }

        return redirect('dashboard/noticias')->with('status', 'Se ha creado la noticia correctamente');
    }

    /**
     * Muestra el formulario para editar una noticia
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::all();
        $post = Post::find($id);
        $categoryIds = [];

        foreach ($post->categories()->get() as $item) {
            array_push($categoryIds, $item->id);
        }

        $post->categoryIds = $categoryIds;

        return view('pages.admin.edit-news', compact('categories', 'post'));
    }

    /**
     * Actualiza la noticia especificada en la base de datos.
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
        //related material
        if ($request->hasFile('related_material')) {
            $this->baseRules['related_material'] = 'required|file';
        }

        $validator = Validator::make($request->all(), $this->baseRules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $post = Post::find($id);

        $toUpdate = [
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'description' => $request->description,
            'slug' => str_slug($request->title, '-'),
        ];

        if ($request->hasFile('image')) {
            if (file_exists(storage_path('app/public/'.$post->image))) {
                unlink(storage_path('app/public/'.$post->image));
            }

            $file = $request->file('image');
            $uploadPath = $file->store('uploads');
            $toUpdate['image'] = $uploadPath;
        }

        if ($request->hasFile('related_material')) {
            if (file_exists(storage_path('app/public/'.$post->related_material))) {
                unlink(storage_path('app/public/'.$post->related_material));
            }

            $file_material = $request->file('related_material');
            $uploadPathMaterial = $file_material->store('uploads');
            $type = Post::typeRelatedMaterial($file_material);
            $toUpdate['related_material'] = $uploadPathMaterial;
            $toUpdate['type_related_material'] = $type;
        }

        $categoryIds = $request->input('categories');

        if (!is_null($categoryIds)) {
            CategoriesController::updatePostCategories($categoryIds, $post);
        }

        $post->update($toUpdate);

        return redirect('dashboard/noticias')->with('status', 'Se ha editado la noticia correctamente');
    }
}
