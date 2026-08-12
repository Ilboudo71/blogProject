<?php

use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductLikeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketplaceController::class, 'home'])->name('marketplace.home');
Route::get('/produits/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');
Route::post('/produits/{product}/like', [ProductLikeController::class, 'toggle'])->name('marketplace.like');
Route::get('/espace', [MarketplaceController::class, 'loginRedirect'])->name('marketplace.espace');
Route::get('/a-propos', [MarketplaceController::class, 'about'])->name('marketplace.about');
Route::get('/politique-de-confidentialite', [MarketplaceController::class, 'privacy'])->name('marketplace.privacy');
Route::get('/conditions-d-utilisation', [MarketplaceController::class, 'terms'])->name('marketplace.terms');
