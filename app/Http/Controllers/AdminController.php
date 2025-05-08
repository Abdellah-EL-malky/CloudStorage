<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\File;
use App\Models\Share;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('last_login')->count();


        $totalStorage = User::sum('storage_used');

        $monthlyRevenue = $this->calculateRevenue();

        $planDistribution = Plan::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalStorage',
            'monthlyRevenue',
            'planDistribution'
        ));
    }


    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->input('type') !== 'all') {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'active') {
                $query->whereNotNull('last_login');
            } else {
                $query->whereNull('last_login');
            }
        }

        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_dir', 'desc');

        $users = $query->orderBy($sortField, $sortDirection)->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function activity()
    {
        // Uploads récents
        $recentUploads = File::with('user')->latest()->take(10)->get();

        // Partages récents
        $recentShares = Share::with(['user', 'shareable'])->latest()->take(10)->get();

        return view('admin.activity', compact('recentUploads', 'recentShares'));
    }

    private function calculateRevenue()
    {
        $premiumUsers = Plan::where('type', 'premium')->count();
        $businessUsers = Plan::where('type', 'business')->count();

        return ($premiumUsers * 9.99) + ($businessUsers * 19.99);
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }
}
