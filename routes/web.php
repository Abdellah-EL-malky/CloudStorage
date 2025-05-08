<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Inscription
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Mot de passe oublié
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Réinitialisation du mot de passe
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Routes nécessitant une authentification
Route::middleware('auth')->group(function () {
    // Déconnexion
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Plans et paiements
    Route::get('/plans', [PlanController::class, 'showPlanSelectionForm'])->name('plans');
    Route::post('/plans', [PlanController::class, 'selectPlan'])->name('plans.select');
    Route::get('/payment/show/{plan}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // Fichiers
    Route::resource('files', FileController::class);
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::post('/files/{file}/favorite', [FileController::class, 'toggleFavorite'])->name('files.favorite');

    // Dossiers
    Route::resource('folders', FolderController::class);
    Route::post('/folders/{folder}/favorite', [FolderController::class, 'toggleFavorite'])->name('folders.favorite');

    // Tags
    Route::resource('tags', TagController::class);

    // Partages
    Route::resource('shares', ShareController::class);
    Route::post('/shares/file/{file}', [ShareController::class, 'shareFile'])->name('shares.file');
    Route::post('/shares/folder/{folder}', [ShareController::class, 'shareFolder'])->name('shares.folder');
});

// Routes pour les partages publics
Route::get('/share/{token}', [ShareController::class, 'accessShare'])->name('share.access');
Route::get('/share/{token}/download', [ShareController::class, 'downloadSharedFile'])->name('share.download');
Route::get('/share/{token}/preview', [ShareController::class, 'previewSharedFile'])->name('share.preview');
Route::get('/share/{token}/file/{fileId}/download', [ShareController::class, 'downloadSharedFolderFile'])->name('share.file.download');

// Routes d'administration
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/activity', [AdminController::class, 'activity'])->name('admin.activity');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
