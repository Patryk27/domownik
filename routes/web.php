<?php

use App\Http\Controllers\Dashboard\UserController;

Route::get('/', '\\' . UserController::class . '@actionLogin');