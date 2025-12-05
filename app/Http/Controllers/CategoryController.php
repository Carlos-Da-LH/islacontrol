<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Muestra una lista de categorías.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        // Cargar y pasar las categorías a la vista 'categories.index'
        return view('categories.index', compact('categories'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Almacena una nueva categoría.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validatedData['user_id'] = auth()->id();
        $category = Category::create($validatedData);

        // Siempre redirigir al index de categorías
        return redirect()->route('categories.index')->with('success', 'Categoría creada exitosamente!');
    }

    /**
     * Muestra una categoría específica.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // Esto podría ser usado para una vista de detalle
        return view('categories.show', compact('category'));
    }

    /**
     * Muestra el formulario para editar una categoría.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        // Mostrar el formulario de edición con los datos de la categoría
        return view('categories.edit', compact('category'));
    }

    /**
     * Actualiza una categoría existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // Verificar que la categoría pertenece al usuario autenticado
        if ($category->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar esta categoría.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validatedData);

        // Siempre redirigir al index de categorías
        return redirect()->route('categories.index')->with('success', 'Categoría actualizada exitosamente!');
    }

    /**
     * Elimina una categoría.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // Verificar que la categoría pertenece al usuario autenticado
        if ($category->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar esta categoría.');
        }

        $category->delete();

        // Siempre redirigir al index de categorías
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada exitosamente!');
    }
}