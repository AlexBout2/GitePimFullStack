<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\RepasController;
use App\Http\Controllers\ChevalController;
use App\Http\Controllers\KayakController;
use App\Http\Controllers\BagneController;
use App\Http\Controllers\GarderieController;
use App\Http\Controllers\SejourController;

// Route d'accueil
Route::get('/', function () {
        return view('welcome');
})->name('home');

// Routes pour le module Chambres
Route::get('/chambres', [ChambreController::class, 'index'])->name('chambres.index');
Route::get('/chambres/create', [ChambreController::class, 'create'])->name('chambres.create');
Route::get('/sejour/create', [SejourController::class, 'create'])->name('sejour.create');

Route::post('/sejour', [SejourController::class, 'store'])->name('sejour.store');
Route::get('/repas', [RepasController::class, 'index'])->name('repas.index');
Route::get('/cheval', [ChevalController::class, 'index'])->name('cheval.index');
Route::get('/kayak', [KayakController::class, 'index'])->name('kayak.index');
Route::get('/bagne', [BagneController::class, 'index'])->name('bagne.index');
Route::get('/garderie', [GarderieController::class, 'index'])->name('garderie.index');
