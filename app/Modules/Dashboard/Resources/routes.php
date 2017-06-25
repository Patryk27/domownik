<?php

use App\Modules\Dashboard\Controllers\HelpController;
use App\Modules\Dashboard\Controllers\IndexController;
use App\Modules\Dashboard\Controllers\SearchController;
use App\Modules\Dashboard\Controllers\UserController;

// /dashboard
Route::group(['prefix' => 'dashboard'], function() {
	Route::group(['middleware' => 'web'], function() {
		// /dashboard/user
		Route::group(['prefix' => 'user'], function() {
			// /dashboard/user/login
			Route::get('login', '\\' . UserController::class . '@actionLogin')
				 ->name('dashboard.user.login');

			// /dashboard/user/login
			Route::post('login', '\\' . UserController::class . '@actionPostLogin');
		});
	});

	Route::group(['middleware' => 'auth'], function() {
		// /dashboard/help
		Route::group(['prefix' => 'help'], function() {
			Route::get('error-404', '\\' . HelpController::class . '@actionError404')
				 ->name('dashboard.help.error-404');
		});

		// /dashboard/index
		Route::group(['prefix' => 'index'], function() {
			// /dashboard/index/index
			Route::get('index', '\\' . IndexController::class . '@actionIndex')
				 ->name('dashboard.index.index');
		});

		// /dashboard/search
		Route::group(['prefix' => 'search'], function() {
			// /dashboard/search/find
			Route::post('find', '\\' . SearchController::class . '@actionSearch')
				 ->name('dashboard.search.find');
		});

		// /dashboard/user
		Route::group(['prefix' => 'user'], function() {
			// /dashboard/user/logout
			Route::get('logout', '\\' . UserController::class . '@actionLogout')
				 ->name('dashboard.user.logout');

			// /dashboard/user/list
			Route::get('list', '\\' . UserController::class . '@actionList')
				 ->name('dashboard.user.list');

			// /dashboard/user/create
			Route::get('create', '\\' . UserController::class . '@actionCreate')
				 ->name('dashboard.user.create');

			// /dashboard/user/edit
			Route::get('edit/{user}', '\\' . UserController::class . '@actionEdit')
				 ->name('dashboard.user.edit');

			// /dashboard/user/store
			Route::post('store', '\\' . UserController::class . '@actionStore')
				 ->name('dashboard.user.store');

			// /dashboard/user/delete
			Route::get('delete/{user}', '\\' . UserController::class . '@actionDelete')
				 ->name('dashboard.user.delete');
		});
	});
});