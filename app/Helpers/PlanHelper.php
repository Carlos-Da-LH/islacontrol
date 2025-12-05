<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PlanHelper
{
    /**
     * Obtiene el plan actual del usuario
     */
    public static function getCurrentPlan()
    {
        $user = Auth::user();

        if (!$user) {
            return config('plans.free');
        }

        // Si tiene suscripción activa
        if ($user->subscribed('default')) {
            $subscription = $user->subscription('default');
            $allPlans = config('plans');

            // Buscar el plan por stripe_price_id
            foreach ($allPlans as $key => $plan) {
                if ($plan['stripe_price_id'] === $subscription->stripe_price) {
                    $plan['key'] = $key;
                    return $plan;
                }
            }
        }

        // Si no tiene suscripción, retorna plan gratuito
        return config('plans.free');
    }

    /**
     * Obtiene los límites del plan actual
     */
    public static function getLimits()
    {
        $plan = self::getCurrentPlan();
        return $plan['limits'] ?? [];
    }

    /**
     * Verifica si el usuario puede crear más registros de un tipo
     */
    public static function canCreate($type)
    {
        $limits = self::getLimits();
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        switch ($type) {
            case 'product':
                $limit = $limits['products'] ?? null;
                if ($limit === null) return true; // Ilimitado
                $count = $user->products()->count();
                return $count < $limit;

            case 'customer':
                $limit = $limits['customers'] ?? null;
                if ($limit === null) return true; // Ilimitado
                $count = $user->customers()->count();
                return $count < $limit;

            case 'sale':
                $limit = $limits['sales_per_month'] ?? null;
                if ($limit === null) return true; // Ilimitado

                // Contar ventas del mes actual
                $count = $user->sales()
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', date('m'))
                    ->count();

                return $count < $limit;

            default:
                return true;
        }
    }

    /**
     * Obtiene el contador actual de un tipo
     */
    public static function getCount($type)
    {
        $user = Auth::user();

        if (!$user) {
            return 0;
        }

        switch ($type) {
            case 'product':
                return $user->products()->count();

            case 'customer':
                return $user->customers()->count();

            case 'sale':
                return $user->sales()
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', date('m'))
                    ->count();

            default:
                return 0;
        }
    }

    /**
     * Verifica si tiene acceso a una característica
     */
    public static function hasFeature($feature)
    {
        $limits = self::getLimits();
        return $limits[$feature] ?? false;
    }

    /**
     * Verifica si el usuario tiene plan gratuito
     */
    public static function isFree()
    {
        $user = Auth::user();

        if (!$user) {
            return true;
        }

        return !$user->subscribed('default');
    }

    /**
     * Obtiene el nombre del plan actual
     */
    public static function getPlanName()
    {
        $plan = self::getCurrentPlan();
        return $plan['name'] ?? 'Plan Gratuito';
    }

    /**
     * Verifica si el usuario tiene una suscripción activa
     */
    public static function hasActiveSubscription()
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->subscribed('default');
    }

    /**
     * Obtiene el plan del usuario (alias de getCurrentPlan)
     */
    public static function getUserPlan()
    {
        return self::getCurrentPlan();
    }
}
