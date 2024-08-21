<?php

use App\Http\Controllers\AutoRouteController;
use Illuminate\Support\Facades\Route;

Route::get('/rewrite-route', [AutoRouteController::class, 'rewriteRouteFile']);
