<?php

Route::get('/', '\\' . \App\Modules\Installer\Controllers\InstallController::class . '@actionIndex');