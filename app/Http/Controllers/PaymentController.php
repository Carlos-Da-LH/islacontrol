<?php
// Archivo: app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Exception;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
    }

    /**
     * Display a listing of the payments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    /**
     * Crea un cargo con Stripe y guarda el registro.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPayment(Request $request)
    {
        try {
            // 1. Validar datos de la petición
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method_id' => 'required|string',
                'name' => 'required|string',
                'email' => 'required|email',
                'sale_id' => 'required|exists:sales,id',
            ]);

            $amount = $validated['amount'];
            $paymentMethodId = $validated['payment_method_id'];
            $customerName = $validated['name'];
            $customerEmail = $validated['email'];
            $saleId = $validated['sale_id'];

            // 2. Crear el cargo con Stripe
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100, // en centavos
                'currency' => 'mxn',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'sale_id' => $saleId,
                    'customer_name' => $customerName,
                    'customer_email' => $customerEmail,
                ],
            ]);

            // 3. Guardar en la base de datos
            Payment::create([
                'sale_id' => $saleId,
                'stripe_payment_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $amount,
                'payment_method_type' => $paymentIntent->charges->data[0]->payment_method_details->type ?? 'unknown',
            ]);

            return response()->json([
                'message' => 'Pago procesado y registrado con éxito',
                'paymentIntent' => $paymentIntent->toArray()
            ]);
        } catch (ApiErrorException $e) {
            // Maneja los errores de la API de Stripe
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            // Maneja otros errores
            return response()->json([
                'error' => 'Ocurrió un error inesperado.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
