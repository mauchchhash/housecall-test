<?php

use App\Http\Controllers\RxNorm\DrugsSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publicly accessible API Routes
|--------------------------------------------------------------------------
*/

Route::get('/rx_norm_drugs/search/{drug_name}', DrugsSearchController::class);
