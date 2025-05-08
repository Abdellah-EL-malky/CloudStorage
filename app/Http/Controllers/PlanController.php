<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function showPlanSelectionForm()
    {
        $hideBackButton = Auth::user()->created_at->diffInMinutes(now()) < 5;
        return view('plans.index', compact('hideBackButton'));
    }

    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'in:free,premium,business'],
        ]);

        $user = Auth::user();

        if ($request->plan === 'free') {
            $user->storage_limit = 1073741824;
            $user->save();

            $planModel = Plan::where('type', 'Free')->first();
            if ($planModel) {
                UserPlan::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan_id' => $planModel->id,
                        'start_date' => now(),
                        'end_date' => now()->addYear(),
                        'paymentStatus' => 'none'
                    ]
                );
            }

            return redirect()->route('dashboard')->with('success', 'Votre plan gratuit a été activé.');
        }
        else {
            $planType = ucfirst($request->plan);
            $planModel = Plan::where('type', $planType)->first();

            if (!$planModel) {
                return redirect()->route('plans')->with('error', 'Plan non trouvé.');
            }

            return redirect()->route('payment.show', ['plan' => $planModel->id]);
        }
    }
}
