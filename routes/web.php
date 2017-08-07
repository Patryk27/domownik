<?php

use App\Http\Controllers\Dashboard\AuthController;

Route::get('/', AuthController::class . '@login');