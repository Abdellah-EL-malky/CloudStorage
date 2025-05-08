<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\Folder;
use App\Models\Share;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $storageUsed = $user->storage_used;
        $storageLimit = $user->storage_limit;
        $storagePercentage = $user->getStoragePercentage();

        $recentFiles = $user->files()->latest()->take(5)->get();

        $recentShares = $user->shares()->latest()->take(5)->get();

        $totalFiles = $user->files()->count();
        $totalSharedFiles = $user->shares()->count();

        return view('dashboard', compact(
            'user',
            'storageUsed',
            'storageLimit',
            'storagePercentage',
            'recentFiles',
            'recentShares',
            'totalFiles',
            'totalSharedFiles'
        ));
    }
}
