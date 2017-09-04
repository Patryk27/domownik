<?php

use App\Http\Controllers\Installer\InstallController;

Route::any('{any}', InstallController::class . '@index')
	 ->where('any', '.*');