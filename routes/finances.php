<?php

use App\Http\Controllers\Finances\BudgetController;
use App\Http\Controllers\Finances\TransactionCategoryController;
use App\Http\Controllers\Finances\TransactionController;

// /finances
Route::group(['prefix' => 'finances', 'middleware' => 'auth'], function() {
	// /finances/budgets
	Route::group(['prefix' => 'budgets'], function() {
		// /finances/budgets/{budget}/transactions/booked
		Route::match(['get', 'post'], '{budget}/transactions/booked', BudgetController::class . '@bookedTransactions')
			 ->name('finances.budgets.booked-transactions');

		// /finances/budgets/{budget}/transactions/scheduled
		Route::match(['get', 'post'], '{budget}/transactions/scheduled', BudgetController::class . '@scheduledTransactions')
			 ->name('finances.budgets.scheduled-transactions');
	});

	// /dashboard/budgets
	Route::resource('budgets', BudgetController::class, [
		'names' => [
			'index' => 'finances.budgets.index',
			'create' => 'finances.budgets.create',
			'store' => 'finances.budgets.store',
			'show' => 'finances.budgets.show',
			'edit' => 'finances.budgets.edit',
			'update' => 'finances.budgets.update',
			'destroy' => 'finances.budgets.destroy',
		],
	]);

	// /finances/transaction
	Route::group(['prefix' => 'transaction'], function() {
		// /finances/transaction/create/to-budget
		Route::get('create/to-budget/{budget}', TransactionController::class . '@actionCreateToBudget')
			 ->name('finances.transaction.create-to-budget');

		// /finances/transaction/store
		Route::post('store', TransactionController::class . '@actionStore')
			 ->name('finances.transaction.store');

		// /finances/transaction/delete
		Route::get('delete/{transaction}', TransactionController::class . '@actionDelete')
			 ->name('finances.transaction.delete');

		// /finances/transaction/list/from-budget
		Route::get('list/from-budget/{budget}', TransactionController::class . '@actionListFromBudget')
			 ->name('finances.transaction.list-from-budget');

		// /finances/transaction/edit
		Route::get('edit/{transaction}', TransactionController::class . '@actionEdit')
			 ->name('finances.transaction.edit');

		// /finances/transaction/view
		Route::get('view/{transaction}', TransactionController::class . '@actionView')
			 ->name('finances.transaction.view');
	});

	// /finances/transaction-category
	Route::group(['prefix' => 'transaction-category'], function() {
		// /finances/transaction-category/list
		Route::get('list', TransactionCategoryController::class . '@actionList')
			 ->name('finances.transaction-category.list');

		// /finances/transaction-category/store
		Route::post('store', TransactionCategoryController::class . '@actionStore')
			 ->name('finances.transaction-category.store');
	});
});