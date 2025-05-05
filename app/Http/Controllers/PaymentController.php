<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show(Request $request)
    {
        $plan = $request->input('plan');
        $user = Auth::user();

        $price = $plan === 'premium' ? 19.99 : 29.99;

        // Génére un ID de transaction unique
        $transactionId = 'CS-' . strtoupper(Str::random(10));

        // Stocke l'ID de transaction dans la session
        session(['transaction_id' => $transactionId]);
        session(['plan' => $plan]);

        return view('payment.show', compact('plan', 'price', 'user', 'transactionId'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'in:premium,business'],
            'payment_method' => ['required', 'in:paypal,card'],
        ]);

        $plan = $request->input('plan');
        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'paypal') {
            return redirect()->route('payment.paypal.redirect', ['plan' => $plan]);
        } else {
            $success = true;

            if ($success) {
                return $this->completePayment($plan);
            } else {
                return redirect()->route('payment.failed')
                    ->with('error', 'Le paiement par carte a échoué. Veuillez réessayer.');
            }
        }
    }

    public function redirectToPaypal(Request $request)
    {
        $plan = $request->input('plan');
        $price = $plan === 'premium' ? 19.99 : 29.99;
        $transactionId = session('transaction_id', 'CS-' . strtoupper(Str::random(10)));

        return view('payment.paypal_redirect', compact('plan', 'price', 'transactionId'));
    }

    public function handlePaypalSuccess()
    {
        $plan = session('plan');

        if (!$plan) {
            return redirect()->route('plans')
                ->with('error', 'Session expirée. Veuillez réessayer.');
        }

        return $this->completePayment($plan);
    }

    public function handlePaypalCancel()
    {
        return redirect()->route('plans')
            ->with('error', 'Le paiement PayPal a été annulé. Veuillez réessayer ou choisir un autre mode de paiement.');
    }

    protected function completePayment($plan)
    {
        $user = Auth::user();

        Log::info("Paiement réussi pour l'utilisateur {$user->id} - Plan: {$plan}");

        // Mise à jour du plan de l'utilisateur
        if ($plan === 'premium') {
            $user->account_type = 'premium';
            $user->storage_limit = 5368709120; // 5GB
        } else {
            $user->account_type = 'business';
            $user->storage_limit = 10737418240; // 10GB
        }

        $user->save();

        session()->forget(['transaction_id', 'plan']);

        return redirect()->route('dashboard')
            ->with('success', 'Paiement effectué avec succès. Votre plan ' . ucfirst($plan) . ' est maintenant actif.');
    }

    public function failed()
    {
        return view('payment.failed');
    }

    public function cancel()
    {
        session()->forget(['transaction_id', 'plan']);

        return redirect()->route('plans')
            ->with('error', 'Le paiement a été annulé. Veuillez réessayer ou choisir un autre plan.');
    }
}
