<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LivreController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

// Page accueil
Route::get('/', fn() => redirect()->route('livres.index'));

// ----------------------
// Routes publiques
// ----------------------
Route::get('/livres', [LivreController::class, 'index'])->name('livres.index');
    Route::get('/livres/create', [LivreController::class, 'create'])->name('livres.create');
Route::get('/livres/{livre}', [LivreController::class, 'show'])->name('livres.show');
Route::get('/nouveautes', [LivreController::class, 'nouveautes'])->name('livres.nouveautes');
Route::get('/search', [LivreController::class, 'searchForm'])->name('livres.search.form');
Route::get('/resultats', [LivreController::class, 'search'])->name('livres.search');

// Contact
Route::get('/contact', [MessageController::class, 'create'])->name('messages.create');
Route::post('/contact', [MessageController::class, 'store'])->name('messages.store');

// ----------------------
// Auth
// ----------------------
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Demande du lien de réinitialisation
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

// Réinitialisation avec le token reçu par mail
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

// ----------------------
// Routes protégées (auth)
// ----------------------
Route::middleware('auth')->group(function () {



    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mot de passe
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Vérification email
    Route::get('/email/verify', fn() => view('auth.verify-email'))->name('verification.notice');
    Route::post('/email/verification-notification', [ProfileController::class, 'sendVerificationEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});



// ----------------------
// Routes admin
// ----------------------
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::post('/livres', [LivreController::class, 'store'])->name('livres.store');
    Route::get('/livres/{livre}/edit', [LivreController::class, 'edit'])->name('livres.edit');
    Route::put('/livres/{livre}', [LivreController::class, 'update'])->name('livres.update');
    Route::delete('/livres/{livre}', [LivreController::class, 'destroy'])->name('livres.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// Panier
Route::middleware('auth')->group(function () {
    Route::get('/panier', [PanierController::class, 'index'])->name('panier.index');
    Route::post('/panier/ajouter/{id}', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::delete('/panier/retirer/{id}', [PanierController::class, 'retirer'])->name('panier.retirer');
    Route::delete('/panier/vider', [PanierController::class, 'vider'])->name('panier.vider');
});

// Paiement
Route::middleware('auth')->group(function () {
    // Routes PayPal
    Route::post('/paypal/checkout', [PaymentController::class, 'checkout'])->name('paypal.checkout');
    Route::post('/paypal/capture', [PaymentController::class, 'capture'])->name('paypal.capture');

    // Routes Stripe
    Route::post('/stripe/create-payment-intent', [PaymentController::class, 'createStripePaymentIntent'])->name('stripe.create-payment-intent');
    Route::post('/stripe/confirm-payment', [PaymentController::class, 'confirmStripePayment'])->name('stripe.confirm-payment');

    // Routes de retour
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

Route::middleware('auth')->get('/historique', [HistoriqueController::class, 'index'])->name('achats.historique');

Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [PasswordController::class, 'edit'])->name('password.edit');
    Route::post('/password/change', [PasswordController::class, 'update'])->name('password.update');
});
