<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Validator;

class CategoriesController extends Controller
{
    public function showCreateCategory()
    {
        return view('pages.admin.create-category');
    }

    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:180',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Category::create([
            'name' => $request->input('category_name')
        ]);

        return redirect('dashboard/noticias')->with('status', 'Se ha creado la categoría correctamente');
    }

    /**
     * Actualiza las categorías en una publicación.
     *
     * @param  array  $categoryIds
     * @param  \App\Post  $post
     * @return void
     */
    public static function updatePostCategories($categoryIds, $post)
    {
        if (count($categoryIds)) {
            $post->categories()->detach();

            foreach ($categoryIds as $id) {
                $category = Category::find($id);
                $post->categories()->attach($category);
            }
        }
    }
}
