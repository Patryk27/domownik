<?php

use App\Http\Controllers\Finances\Budget\TransactionController as BudgetTransactionController;
use App\Http\Controllers\Finances\BudgetController;
use App\Http\Controllers\Finances\Transaction\BudgetController as TransactionBudgetController;
use App\Http\Controllers\Finances\TransactionCategoryController;
use App\Http\Controllers\Finances\TransactionController;

// /finances
Route::group(['prefix' => 'finances', 'middleware' => 'auth'], function() {
	// /finances/budgets
	Route::group(['prefix' => 'budgets'], function() {
		// /finances/budgets/{budget}/transactions
		Route::group(['prefix' => '{budget}/transactions'], function() {
			// /finances/budgets/{budget}/transactions/booked
			Route::match(['get', 'post'], 'booked', BudgetTransactionController::class . '@booked')
				 ->name('finances.budgets.transactions.booked');

			// /finances/budgets/{budget}/transactions/scheduled
			Route::match(['get', 'post'], 'scheduled', BudgetTransactionController::class . '@scheduled')
				 ->name('finances.budgets.transactions.scheduled');
		});

		// /finances/budgets/{budget}/transactions
		Route::resource('/{budget}/transactions', TransactionBudgetController::class, [
			'only' => [
				'index',
				'create',
				'edit',
			],

			'names' => [
				'index' => 'finances.budgets.transactions.index',
				'create' => 'finances.budgets.transactions.create',
				'edit' => 'finances.budgets.transactions.edit',
			],
		]);
	});

	// /finances/budgets
	Route::resource('budgets', BudgetController::class, [
		'names' => [
			'index' => 'finances.budgets.index',
			'create' => 'finances.budgets.create',
			'store' => 'finances.budgets.store',
			'show' => 'finances.budgets.show',
			'edit' => 'finances.budgets.edit',
			'update' => 'finances.budgets.update',
			'destroy' => 'finances.budgets.destroy', // @todo create this method
		],
	]);

	// /finances/transactions
	Route::resource('transactions', TransactionController::class, [
		'only' => [
			'store',
			'show',
			'edit',
			'update',
			'destroy',
		],

		'names' => [
			'store' => 'finances.transactions.store',
			'show' => 'finances.transactions.show',
			'edit' => 'finances.transactions.edit',
			'update' => 'finances.transactions.update',
			'destroy' => 'finances.transactions.destroy', // @todo create this method
		],
	]);

	// /finances/transaction-categories
	Route::resource('transaction-categories', TransactionCategoryController::class, [
		'only' => [
			'index',
			'store',
		],

		'names' => [
			'index' => 'finances.transaction-categories.index',
			'store' => 'finances.transaction-categories.store',
		],
	]);
});