<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashRegisterController extends Controller
{
    /**
     * Mostrar historial de cajas
     */
    public function index()
    {
        $cashRegisters = CashRegister::where('user_id', Auth::id())
            ->with('cashier')
            ->orderBy('opened_at', 'desc')
            ->paginate(20);

        return view('cash-register.index', compact('cashRegisters'));
    }

    /**
     * Mostrar formulario para abrir caja
     */
    public function create()
    {
        // Verificar si ya hay una caja abierta
        $openCashRegister = CashRegister::getOpenCashRegister(Auth::id());

        if ($openCashRegister) {
            return redirect()->route('cash-register.show', $openCashRegister->id)
                ->with('warning', 'Ya tienes una caja abierta.');
        }

        // Obtener cajeros activos
        $cashiers = \App\Models\Cashier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return view('cash-register.create', compact('cashiers'));
    }

    /**
     * Guardar apertura de caja
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'cashier_id' => 'nullable|exists:cashiers,id',
            'opening_notes' => 'nullable|string|max:500',
        ]);

        // Verificar nuevamente que no haya caja abierta
        $openCashRegister = CashRegister::getOpenCashRegister(Auth::id());
        if ($openCashRegister) {
            return redirect()->route('cash-register.show', $openCashRegister->id)
                ->with('warning', 'Ya tienes una caja abierta.');
        }

        $cashRegister = CashRegister::create([
            'user_id' => Auth::id(),
            'cashier_id' => $validated['cashier_id'] ?? null,
            'opening_balance' => $validated['opening_balance'],
            'opening_notes' => $validated['opening_notes'] ?? null,
            'opened_at' => now(),
            'status' => 'open',
        ]);

        return redirect()->route('dashboard')
            ->with('success', '¡Caja abierta exitosamente! Fondo inicial: $' . number_format($validated['opening_balance'], 2));
    }

    /**
     * Ver detalles de una caja
     */
    public function show($id)
    {
        $cashRegister = CashRegister::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['sales' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        // Actualizar totales si está abierta
        if ($cashRegister->isOpen()) {
            $cashRegister->updateSalesTotals();
            $cashRegister->refresh();
        }

        return view('cash-register.show', compact('cashRegister'));
    }

    /**
     * Mostrar formulario de cierre de caja
     */
    public function closeForm($id)
    {
        $cashRegister = CashRegister::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'open')
            ->with('cashier')
            ->firstOrFail();

        // Actualizar totales antes de cerrar
        $cashRegister->updateSalesTotals();
        $cashRegister->refresh();

        // Obtener cajeros activos
        $cashiers = \App\Models\Cashier::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();

        return view('cash-register.close', compact('cashRegister', 'cashiers'));
    }

    /**
     * Guardar cierre de caja
     */
    public function close(Request $request, $id)
    {
        $validated = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'cashier_id' => 'nullable|exists:cashiers,id',
            'closing_notes' => 'nullable|string|max:500',
        ]);

        $cashRegister = CashRegister::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'open')
            ->firstOrFail();

        // Actualizar totales finales
        $cashRegister->updateSalesTotals();

        // Calcular diferencia
        $expectedBalance = $cashRegister->calculateExpectedBalance();
        $difference = $validated['closing_balance'] - $expectedBalance;

        $cashRegister->update([
            'cashier_id' => $validated['cashier_id'] ?? $cashRegister->cashier_id,
            'closing_balance' => $validated['closing_balance'],
            'closing_notes' => $validated['closing_notes'] ?? null,
            'closed_at' => now(),
            'status' => 'closed',
            'difference' => $difference,
        ]);

        $message = '¡Caja cerrada exitosamente!';
        if ($difference > 0) {
            $message .= ' Sobrante: $' . number_format($difference, 2);
        } elseif ($difference < 0) {
            $message .= ' Faltante: $' . number_format(abs($difference), 2);
        }

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cash_register' => $cashRegister,
            ]);
        }

        return redirect()->route('cash-register.show', $id)
            ->with('success', $message);
    }

    /**
     * Actualizar información de caja
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cashier_id' => 'nullable|exists:cashiers,id',
            'opening_notes' => 'nullable|string|max:500',
            'closing_notes' => 'nullable|string|max:500',
        ]);

        $cashRegister = CashRegister::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cashRegister->update($validated);

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro de caja actualizado exitosamente.',
                'cash_register' => $cashRegister,
            ]);
        }

        return redirect()->route('cash-register.show', $id)
            ->with('success', 'Registro de caja actualizado exitosamente.');
    }

    /**
     * Eliminar un registro de caja
     */
    public function destroy($id)
    {
        $cashRegister = CashRegister::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // No permitir eliminar caja abierta
        if ($cashRegister->isOpen()) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar una caja abierta. Debes cerrarla primero.',
                ], 400);
            }
            return redirect()->route('cash-register.index')
                ->with('error', 'No se puede eliminar una caja abierta. Debes cerrarla primero.');
        }

        $cashRegister->delete();

        // Si es una petición AJAX, retornar JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro de caja eliminado exitosamente.',
            ]);
        }

        return redirect()->route('cash-register.index')
            ->with('success', 'Registro de caja eliminado exitosamente.');
    }

    /**
     * API: Obtener estado de caja del usuario
     */
    public function status()
    {
        $cashRegister = CashRegister::getOpenCashRegister(Auth::id());

        if (!$cashRegister) {
            return response()->json([
                'has_open_register' => false,
                'message' => 'No hay caja abierta',
            ]);
        }

        $cashRegister->updateSalesTotals();
        $cashRegister->refresh();

        return response()->json([
            'has_open_register' => true,
            'cash_register' => $cashRegister,
        ]);
    }

    /**
     * API: Obtener historial de cajas del usuario
     */
    public function getHistory()
    {
        $cashRegisters = CashRegister::where('user_id', Auth::id())
            ->with('cashier')
            ->orderBy('opened_at', 'desc')
            ->get();

        $stats = [
            'open_count' => $cashRegisters->where('status', 'open')->count(),
            'closed_count' => $cashRegisters->where('status', 'closed')->count(),
            'total_sales' => $cashRegisters->sum('total_sales'),
            'total_count' => $cashRegisters->count(),
        ];

        return response()->json([
            'success' => true,
            'cash_registers' => $cashRegisters,
            'stats' => $stats,
        ]);
    }
}
