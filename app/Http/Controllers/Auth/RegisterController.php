<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Crée l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => 'free',
            'storage_limit' => 1073741824, // 1GB
        ]);

        // Assigne le rôle de lecteur par défaut
        $readerRole = Role::where('name', 'reader')->first();
        $user->roles()->attach($readerRole);

        // Connexion automatique
        Auth::login($user);

        return redirect(route('plans'));
    }

    public function showPlanSelectionForm()
    {
        return view('auth.plans');
    }

    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'in:free,premium,business'],
        ]);

        $user = Auth::user();

        switch ($request->plan) {
            case 'premium':
                $user->account_type = 'premium';
                $user->storage_limit = 5368709120; // 5GB
                return redirect()->route('payment.show', ['plan' => 'premium']);
                break;
            case 'business':
                $user->account_type = 'business';
                $user->storage_limit = 10737418240; // 10GB
                return redirect()->route('payment.show', ['plan' => 'business']);
                break;
            default:
                $user->account_type = 'free';
                $user->storage_limit = 1073741824; // 1GB
                return redirect()->route('dashboard')->with('success', 'Votre plan gratuit a été activé.');
        }

        $user->save();

        return redirect(route('dashboard'))->with('success', 'Votre plan a été mis à jour avec succès.');
    }
}
