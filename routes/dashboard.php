<?php

use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\HelpController;
use App\Http\Controllers\Dashboard\IndexController;
use App\Http\Controllers\Dashboard\SearchController;
use App\Http\Controllers\Dashboard\UserController;

// /dashboard
Route::group(['prefix' => 'dashboard'], function() {
	Route::group(['middleware' => 'web'], function() {
		// /dashboard/user
		Route::group(['prefix' => 'auth'], function() {
			// /dashboard/auth/login
			Route::get('login', AuthController::class . '@login')
				 ->name('dashboard.auth.login');

			// /dashboard/auth/login
			Route::post('login', AuthController::class . '@postLogin');

			// /dashboard/auth/logout
			Route::get('logout', AuthController::class . '@logout')
				 ->name('dashboard.auth.logout');
		});
	});

	Route::group(['middleware' => 'auth'], function() {
		// /dashboard/help
		Route::group(['prefix' => 'help'], function() {
			// /dashboard/help/http-errors/{errorCode}
			Route::get('http-errors/{errorCode}', HelpController::class . '@httpError')
				 ->name('dashboard.help.http-errors')
				 ->where('errorCode', '[0-9]{3}');
		});

		// /dashboard/index
		Route::group(['prefix' => 'index'], function() {
			// /dashboard/index/index
			Route::get('index', IndexController::class . '@index')
				 ->name('dashboard.index.index');
		});

		// /dashboard/search
		Route::group(['prefix' => 'search'], function() {
			// /dashboard/search/find
			Route::post('find', SearchController::class . '@search')
				 ->name('dashboard.search.find');
		});

		// /dashboard/users
		Route::resource('users', UserController::class, [
			'names' => [
				'index' => 'dashboard.users.index',
				'create' => 'dashboard.users.create',
				'store' => 'dashboard.users.store',
				'show' => 'dashboard.users.show',
				'edit' => 'dashboard.users.edit',
				'update' => 'dashboard.users.update',
				'destroy' => 'dashboard.users.destroy',
			],
		]);
	});
});