<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Helpers\PlanHelper;

class CustomerController extends Controller
{
    /**
     * Muestra una lista de clientes de la base de datos.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los clientes ordenando "Público General" primero
        $customers = Customer::where('user_id', auth()->id())
            ->orderByRaw("CASE WHEN name = 'Público General' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

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
        // Verificar límites del plan
        if (!PlanHelper::canCreate('customer')) {
            $limits = PlanHelper::getLimits();
            $current = PlanHelper::getCount('customer');

            // Si es petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'limit_reached' => true,
                    'type' => 'customer',
                    'limit' => $limits['customers'],
                    'current' => $current,
                    'message' => "Has alcanzado el límite de {$limits['customers']} clientes de tu plan."
                ], 403);
            }

            return redirect()->route('customers.index')->with('limit_reached', [
                'type' => 'customer',
                'limit' => $limits['customers'],
                'current' => $current
            ])->withInput();
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers',
        ]);

        $validatedData['user_id'] = auth()->id();
        Customer::create($validatedData);

        // Siempre redirigir al index de clientes
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
        // Buscar el cliente por su nombre y que pertenezca al usuario autenticado
        $customer = Customer::where('name', $name)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();

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
        // Verificar que el cliente pertenece al usuario autenticado
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este cliente.');
        }

        // Proteger el cliente "Público General"
        if ($customer->name === 'Público General') {
            return redirect()->route('customers.index')
                ->with('error', 'No se puede editar el cliente "Público General" ya que es un cliente del sistema.');
        }

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
        // Verificar que el cliente pertenece al usuario autenticado
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para editar este cliente.');
        }

        // Proteger el cliente "Público General"
        if ($customer->name === 'Público General') {
            return redirect()->route('customers.index')
                ->with('error', 'No se puede editar el cliente "Público General" ya que es un cliente del sistema.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($validatedData);

        // Siempre redirigir al index de clientes
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
        // Verificar que el cliente pertenece al usuario autenticado
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para eliminar este cliente.');
        }

        // Proteger el cliente "Público General"
        if ($customer->name === 'Público General') {
            return redirect()->route('customers.index')
                ->with('error', 'No se puede eliminar el cliente "Público General" ya que es un cliente del sistema.');
        }

        $customer->delete();

        // Siempre redirigir al index de clientes
        return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente!');
    }
}