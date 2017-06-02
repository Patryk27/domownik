<?php

Route::get('/', '\\' . \App\Modules\Dashboard\Controllers\UserController::class . '@actionLogin');