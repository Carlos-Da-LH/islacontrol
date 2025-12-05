<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function selectPlan()
    {
        return view('subscriptions.select-plan');
    }

    public function plans()
    {
        $plans = config('plans');
        return view('subscriptions.plans', compact('plans'));
    }

    public function checkout(Request $request, $plan)
    {
        $planConfig = config("plans.{$plan}");

        if (!$planConfig) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Plan no válido');
        }

        $user = Auth::user();

        if ($user->subscribed('default')) {
            return redirect()->route('subscription.dashboard')
                ->with('info', 'Ya tienes una suscripción activa');
        }

        return view('subscriptions.checkout', [
            'plan' => $plan,
            'planConfig' => $planConfig,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function subscribe(Request $request, $plan)
    {
        $planConfig = config("plans.{$plan}");

        if (!$planConfig) {
            return response()->json([
                'error' => 'Plan no válido'
            ], 400);
        }

        $user = Auth::user();

        try {
            $subscription = $user->newSubscription('default', $planConfig['stripe_price_id']);

            // Solo aplicar trial si está configurado Y es mayor a 0
            if (isset($planConfig['trial_days']) && $planConfig['trial_days'] > 0) {
                $subscription->trialDays($planConfig['trial_days']);
            }
            // Si no hay trial_days o es 0, simplemente no llamamos trialDays()
            // Esto hará que Stripe cobre inmediatamente

            $subscription->create($request->payment_method);

            return response()->json([
                'success' => true,
                'message' => '¡Suscripción creada exitosamente!',
                'redirect' => route('subscription.dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la suscripción: ' . $e->getMessage()
            ], 500);
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->subscribed('default')) {
            return redirect()->route('subscription.plans')
                ->with('info', 'No tienes una suscripción activa');
        }

        $subscription = $user->subscription('default');
        $plan = $this->getPlanFromPrice($subscription->stripe_price);

        return view('subscriptions.dashboard', [
            'subscription' => $subscription,
            'plan' => $plan,
            'planConfig' => config("plans.{$plan}"),
        ]);
    }

    public function cancel(Request $request)
    {
        $user = Auth::user();

        if (!$user->subscribed('default')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'No tienes una suscripción activa');
        }

        $user->subscription('default')->cancel();

        return redirect()->route('subscription.dashboard')
            ->with('success', 'Tu suscripción se cancelará al final del período actual');
    }

    public function resume(Request $request)
    {
        $user = Auth::user();

        if (!$user->subscription('default')->canceled()) {
            return redirect()->route('subscription.dashboard')
                ->with('error', 'Tu suscripción no está cancelada');
        }

        $user->subscription('default')->resume();

        return redirect()->route('subscription.dashboard')
            ->with('success', 'Tu suscripción ha sido reactivada');
    }

    public function swap(Request $request, $plan)
    {
        $planConfig = config("plans.{$plan}");

        if (!$planConfig) {
            return redirect()->route('subscription.dashboard')
                ->with('error', 'Plan no válido');
        }

        $user = Auth::user();

        if (!$user->subscribed('default')) {
            return redirect()->route('subscription.plans')
                ->with('error', 'No tienes una suscripción activa');
        }

        try {
            $user->subscription('default')->swap($planConfig['stripe_price_id']);

            return redirect()->route('subscription.dashboard')
                ->with('success', '¡Plan cambiado exitosamente!');
        } catch (\Exception $e) {
            return redirect()->route('subscription.dashboard')
                ->with('error', 'Error al cambiar el plan: ' . $e->getMessage());
        }
    }

    public function invoices()
    {
        $user = Auth::user();
        $invoices = $user->invoices();

        return view('subscriptions.invoices', compact('invoices'));
    }

    public function downloadInvoice($invoiceId)
    {
        $user = Auth::user();

        return $user->downloadInvoice($invoiceId, [
            'vendor' => 'IslaControl',
            'product' => 'Suscripción',
        ]);
    }

    public function webhook(Request $request)
    {
        return $this->handleWebhook($request);
    }

    private function getPlanFromPrice($priceId)
    {
        $plans = config('plans');

        foreach ($plans as $key => $plan) {
            if ($plan['stripe_price_id'] === $priceId) {
                return $key;
            }
        }

        return 'basico';
    }
}
