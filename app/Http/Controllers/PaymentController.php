<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Payment;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{

    public function show(Request $request, $plan)
    {
        if (is_numeric($plan)) {
            $planModel = Plan::find($plan);
        } else {
            $planType = ucfirst($plan);
            $planModel = Plan::where('type', $planType)->first();
        }

        if (!$planModel) {
            return redirect()->route('plans')->with('error', 'Plan non trouvé');
        }

        return view('payment.show', ['planModel' => $planModel]);
    }

    public function process(Request $request)
    {
        $planId = $request->plan_id;
        $plan = Plan::find($planId);

        if (!$plan) {
            return redirect()->route('plans')->with('error', 'Plan non trouvé');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('payment.success'),
                "cancel_url" => route('payment.cancel')
            ],
            "purchase_units" => [
                [
                    "reference_id" => $plan->id,
                    "description" => "Abonnement " . $plan->type,
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $plan->price
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            session(['plan_id' => $plan->id]);
            session(['paypal_order_id' => $response['id']]);

            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->back()->with('error', 'Impossible de créer la commande PayPal');
    }

    public function success(Request $request)
    {
        $planId = session('plan_id');
        $token = $request->get('token');

        if (!$planId || !$token) {
            return redirect()->route('plans')->with('error', 'Informations de paiement manquantes');
        }

        $plan = Plan::find($planId);

        if (!$plan) {
            return redirect()->route('plans')->with('error', 'Plan non trouvé');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        try {
            if (Payment::where('payment_id', $token)->exists()) {
                return redirect()->route('dashboard')->with('info', 'Paiement déjà traité.');
            }

            $response = $provider->capturePaymentOrder($token);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $user = Auth::user();

                $user->storage_limit = $plan->storage;
                $user->save();

                $userPlan = new UserPlan([
                    'plan_id' => $planId,
                    'user_id' => $user->id,
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'paymentStatus' => 'paid'
                ]);
                $userPlan->save();

                $payment = new Payment([
                    'payment_id' => $token,
                    'plan_id' => $planId,
                    'user_id' => $user->id,
                    'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                    'currency' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                    'payment_status' => $response['status'],
                    'payment_method' => 'PayPal'
                ]);
                $payment->save();

                session()->forget(['plan_id', 'paypal_order_id']);

                return redirect()->route('dashboard')->with('success', 'Paiement réussi ! Votre plan ' . $plan->type . ' est maintenant actif.');
            }

            return redirect()->route('dashboard')->with('error', 'Le paiement a échoué');

        } catch (\Exception $e) {
            return redirect()->route('plans')->with('error', 'Une erreur est survenue lors du traitement du paiement');
        }
    }

    public function cancel(Request $request)
    {
        session()->forget(['plan_id', 'paypal_order_id']);

        return redirect()->route('plans')->with('warning', 'Paiement annulé');
    }
}
