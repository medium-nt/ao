<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientDocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceStatusController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false, 'reset' => false, 'confirm' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::post('/clients/{client}/documents', [ClientDocumentController::class, 'store'])->name('client-documents.store');
    Route::get('/documents/{document}/download', [ClientDocumentController::class, 'download'])->name('client-documents.download');
    Route::delete('/documents/{document}', [ClientDocumentController::class, 'destroy'])->name('client-documents.destroy');

    Route::get('/clients/{client}/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/clients/{client}/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/clients/{client}/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/clients/{client}/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/clients/{client}/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/clients/{client}/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/clients/{client}/orders/{order}/change-status', [OrderController::class, 'changeStatus'])->name('orders.change-status');

    Route::post('/clients/{client}/orders/{order}/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::delete('/clients/{client}/orders/{order}/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
});

Route::middleware('auth')->can('is-admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/documents/{document}/approve', [ClientDocumentController::class, 'approve'])->name('client-documents.approve');

    Route::resource('services', ServiceController::class)->except(['show']);

    Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');
    Route::post('/services/{service}/statuses', [ServiceStatusController::class, 'store'])->name('service-statuses.store');
    Route::put('/services/{service}/statuses/{status}', [ServiceStatusController::class, 'update'])->name('service-statuses.update');
    Route::delete('/services/{service}/statuses/{status}', [ServiceStatusController::class, 'destroy'])->name('service-statuses.destroy');
    Route::post('/services/{service}/statuses/reorder', [ServiceStatusController::class, 'reorder'])->name('service-statuses.reorder');
});
