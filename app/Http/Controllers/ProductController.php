<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Muestra una lista de productos y el formulario de creación.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene todos los productos con su categoría relacionada
        // CORREGIDO: Removida la paginación que causaba problemas
        // Los productos más recientes aparecen primero
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        
        // Obtiene todas las categorías para la lista desplegable del formulario.
        $categories = Category::all();

        // Retorna la vista de Blade y le pasa los datos de productos y categorías.
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtiene todas las categorías para el formulario de creación.
        $categories = Category::all();
        // Retorna la vista del formulario para crear un producto, pasando las categorías.
        return view('products.create', compact('categories'));
    }

    /**
     * Almacena un nuevo producto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Crea el nuevo producto en la base de datos.
        Product::create($validatedData);

        // Verificar si viene desde el dashboard
        if ($request->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Producto creado exitosamente!');
        }

        // Redirige al usuario de vuelta a la página principal de productos
        // con un mensaje de éxito.
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente!');
    }

    /**
     * Muestra un producto específico (no se usa en este caso).
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Este método no es necesario para la interfaz actual,
        // pero se mantiene para la estructura de recurso.
        return view('products.show', compact('product'));
    }

    /**
     * Muestra el formulario para editar un producto.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // Obtiene todas las categorías para la lista desplegable de edición.
        $categories = Category::all();

        // Retorna la vista de edición con el producto y las categorías.
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Actualiza un producto existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Valida los datos de la solicitud.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Actualiza el producto en la base de datos.
        $product->update($validatedData);

        // Verificar si viene desde el dashboard
        if ($request->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Producto actualizado exitosamente!');
        }

        // Redirige al usuario de vuelta a la página principal de productos
        // con un mensaje de éxito.
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente!');
    }

    /**
     * Elimina un producto.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Elimina el producto de la base de datos.
        $product->delete();

        // Verificar si viene desde el dashboard
        if (request()->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Producto eliminado exitosamente!');
        }

        // Redirige al usuario de vuelta a la página principal de productos
        // con un mensaje de éxito.
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente!');
    }
}