<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Muestra una lista de clientes de la base de datos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los clientes de la base de datos
        $customers = Customer::all();

        // Devolver la vista 'customers.index' y pasar los clientes
        return view('customers.index', [
            'customers' => $customers
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers',
        ]);

        Customer::create($validatedData);

        // Verificar si viene desde el dashboard
        if ($request->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Cliente creado exitosamente!');
        }

        return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente!');
    }

    /**
     * Muestra un cliente específico buscando por su nombre.
     *
     * @param  string  $name El nombre del cliente.
     * @return \Illuminate\View\View
     */
    public function show($name)
    {
        // Buscar el cliente por su nombre. Si no se encuentra, abortar con un error 404.
        $customer = Customer::where('name', $name)->firstOrFail();

        return view('customers.show', compact('customer'));
    }

    /**
     * Muestra el formulario para editar un cliente existente.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\View\View
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Actualiza un cliente existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($validatedData);

        // Verificar si viene desde el dashboard
        if ($request->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Cliente actualizado exitosamente!');
        }

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente!');
    }

    /**
     * Elimina un cliente de la base de datos.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        // Verificar si viene desde el dashboard
        if (request()->input('from') === 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Cliente eliminado exitosamente!');
        }
        
        return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente!');
    }
}