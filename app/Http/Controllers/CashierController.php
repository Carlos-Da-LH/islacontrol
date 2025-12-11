<?php

namespace App\Http\Controllers;

use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PlanHelper;

class CashierController extends Controller
{
    /**
     * Mostrar lista de cajeros
     */
    public function index(Request $request)
    {
        // Obtener el valor de paginación del request o usar el default
        $perPage = PlanHelper::validatePaginationValue(
            $request->input('per_page', PlanHelper::getDefaultPagination())
        );

        // Query base
        $query = Cashier::where('user_id', Auth::id())
            ->orderBy('name', 'asc');

        // Aplicar búsqueda si existe
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Paginar resultados
        $cashiers = $query->paginate($perPage)->withQueryString();

        // Obtener opciones de paginación según el plan
        $paginationOptions = PlanHelper::getPaginationOptions();

        return view('cashiers.index', compact('cashiers', 'paginationOptions'));
    }

    /**
     * Mostrar formulario para crear cajero
     */
    public function create()
    {
        return view('cashiers.create');
    }

    /**
     * Guardar nuevo cajero
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';

        $cashier = Cashier::create($validated);

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cajero creado exitosamente.',
                'cashier_id' => $cashier->id,
            ]);
        }

        return redirect()->route('cashiers.index')
            ->with('success', 'Cajero creado exitosamente!');
    }

    /**
     * Mostrar cajero específico
     */
    public function show(Cashier $cashier)
    {
        // Verificar que el cajero pertenece al usuario autenticado
        if ($cashier->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este cajero.');
        }

        return view('cashiers.show', compact('cashier'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Cashier $cashier)
    {
        // Verificar que el cajero pertenece al usuario autenticado
        if ($cashier->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este cajero.');
        }

        return view('cashiers.edit', compact('cashier'));
    }

    /**
     * Actualizar cajero
     */
    public function update(Request $request, Cashier $cashier)
    {
        // Verificar que el cajero pertenece al usuario autenticado
        if ($cashier->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para actualizar este cajero.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string|max:500',
        ]);

        $cashier->update($validated);

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cajero actualizado exitosamente.',
            ]);
        }

        return redirect()->route('cashiers.index')
            ->with('success', 'Cajero actualizado exitosamente!');
    }

    /**
     * Eliminar cajero
     */
    public function destroy(Cashier $cashier)
    {
        // Verificar que el cajero pertenece al usuario autenticado
        if ($cashier->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este cajero.');
        }

        $cashier->delete();

        // Si es una petición AJAX, retornar JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cajero eliminado exitosamente.',
            ]);
        }

        return redirect()->route('cashiers.index')
            ->with('success', 'Cajero eliminado exitosamente!');
    }

    /**
     * API: Obtener lista de cajeros activos
     */
    public function getActive()
    {
        $cashiers = Cashier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'cashiers' => $cashiers,
        ]);
    }
}
