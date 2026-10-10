<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEvent;
use App\Http\Controllers\Organizer\PromoCodeController;
use App\Http\Controllers\Organizer\ScannerController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicOrganizerController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// --- Acheteurs (sans compte) ---
Route::get('/', [PublicEventController::class, 'index'])->name('home');
Route::get('/organisateurs/{user}', [PublicOrganizerController::class, 'show'])->name('organizers.show');
Route::get('/evenements/{event}', [PublicEventController::class, 'show'])->name('events.show');
Route::post('/evenements/{event}/acheter', [CheckoutController::class, 'store'])
    ->middleware('throttle:10,1')->name('checkout.store');
Route::get('/paiement/{order:reference}/retour', [CheckoutController::class, 'result'])->name('checkout.return');
Route::post('/paydunya/ipn', [CheckoutController::class, 'ipn'])->name('paydunya.ipn');
Route::get('/billets/rechercher', [TicketController::class, 'lookup'])->name('tickets.lookup');
Route::post('/billets/rechercher', [TicketController::class, 'searchByPhone'])->name('tickets.search');
Route::get('/billets/{order:reference}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('/billets/{order:reference}/pdf', [TicketController::class, 'pdf'])->name('tickets.pdf');
Route::get('/billets/{order:reference}/calendar.ics', [TicketController::class, 'calendar'])->name('tickets.calendar');

// --- Connexion ---
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:8,1');
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register'])->middleware('throttle:6,1');
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// --- Organisateur ---
Route::middleware(['auth', 'role:organizer,admin'])->prefix('organisateur')->name('organizer.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/evenements/nouveau', [OrganizerEvent::class, 'create'])->name('events.create');
    Route::post('/evenements', [OrganizerEvent::class, 'store'])->name('events.store');
    Route::get('/evenements/{event}', [OrganizerEvent::class, 'show'])->name('events.show');
    Route::get('/evenements/{event}/modifier', [OrganizerEvent::class, 'edit'])->name('events.edit');
    Route::put('/evenements/{event}', [OrganizerEvent::class, 'update'])->name('events.update');
    Route::get('/evenements/{event}/scanner', [ScannerController::class, 'page'])->name('scan');
    Route::get('/evenements/{event}/scanner/donnees', [ScannerController::class, 'data'])->name('scan.data');
    Route::post('/evenements/{event}/scanner', [ScannerController::class, 'check'])->name('scan.check');
    Route::post('/evenements/{event}/codes-promo', [PromoCodeController::class, 'store'])->name('promo-codes.store');
    Route::delete('/evenements/{event}/codes-promo/{promoCode}', [PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
    Route::get('/evenements/{event}/participants.csv', [OrganizerEvent::class, 'exportCsv'])->name('events.export');
});

// --- Administrateur ---
Route::middleware(['auth', 'role:admin'])->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
});
