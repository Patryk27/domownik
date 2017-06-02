<?php

use App\Modules\Dashboard\Controllers\HelpController;
use App\Modules\Dashboard\Controllers\IndexController;
use App\Modules\Dashboard\Controllers\SearchController;
use App\Modules\Dashboard\Controllers\UserController;

Route::group(['prefix' => 'dashboard'], function() {
	Route::group(['middleware' => 'web'], function() {
		/**
		 * /dashboard/user
		 */
		Route::group(['prefix' => 'user'], function() {
			Route::get('login', '\\' . UserController::class . '@actionLogin')
				 ->name('dashboard.user.login');

			Route::post('login', '\\' . UserController::class . '@actionPostLogin');
		});
	});

	Route::group(['middleware' => 'auth'], function() {
		/**
		 * /dashboard/help
		 */
		Route::group(['prefix' => 'help'], function() {
			Route::get('error-404', '\\' . HelpController::class . '@actionError404')
				 ->name('dashboard.help.error-404');
		});

		/**
		 * /dashboard/index
		 */
		Route::group(['prefix' => 'index'], function() {
			Route::get('index', '\\' . IndexController::class . '@actionIndex')
				 ->name('dashboard.index.index');
		});

		/**
		 * /dashboard/search
		 */
		Route::group(['prefix' => 'search'], function() {
			Route::post('search', '\\' . SearchController::class . '@actionSearch')
				 ->name('dashboard.search.search');
		});

		/**
		 * /dashboard/user
		 */
		Route::group(['prefix' => 'user'], function() {
			Route::get('logout', '\\' . UserController::class . '@actionLogout')
				 ->name('dashboard.user.logout');

			Route::get('dashboard', '\\' . UserController::class . '@actionDashboard')
				 ->name('dashboard.user.dashboard');
		});
	});
});