<?php

use App\Modules\Finances\Controllers\BudgetController;
use App\Modules\Finances\Controllers\TransactionCategoryController;
use App\Modules\Finances\Controllers\TransactionController;

// /finances
Route::group(['prefix' => 'finances', 'middleware' => 'auth'], function() {
	// /finances/budget
	Route::group(['prefix' => 'budget'], function() {
		// /finances/budget/create
		Route::get('create', '\\' . BudgetController::class . '@actionCreate')
			 ->name('finances.budget.create');

		// /finances/budget/store
		Route::post('store', '\\' . BudgetController::class . '@actionStore')
			 ->name('finances.budget.store');

		// /finances/budget/show
		Route::get('show/{budget}', '\\' . BudgetController::class . '@actionShow')
			 ->name('finances.budget.show');
	});

	// /finances/transaction
	Route::group(['prefix' => 'transaction'], function() {
		// /finances/transaction/create/to-budget
		Route::get('create/to-budget/{budget}', '\\' . TransactionController::class . '@actionCreateToBudget')
			 ->name('finances.transaction.createToBudget');

		// /finances/transaction/store
		Route::post('store', '\\' . TransactionController::class . '@actionStore')
			 ->name('finances.transaction.store');

		// /finances/transaction/delete
		Route::get('delete/{transaction}', '\\' . TransactionController::class . '@actionDelete')
			->name('finances.transaction.delete');

		// /finances/transaction/list/from-budget
		Route::get('list/from-budget/{budget}', '\\' . TransactionController::class . '@actionListFromBudget')
			 ->name('finances.transaction.listFromBudget');

		// /finances/transaction/edit
		Route::get('edit/{transaction}', '\\' . TransactionController::class . '@actionEdit')
			->name('finances.transaction.edit');

		// /finances/transaction/view
		Route::get('view/{transaction}', '\\' . TransactionController::class . '@actionView')
			 ->name('finances.transaction.view');
	});

	// /finances/transaction-category
	Route::group(['prefix' => 'transaction-category'], function() {
		// /finances/transaction-category/list
		Route::get('list', '\\' . TransactionCategoryController::class . '@actionList')
			->name('finances.transaction-category.list');

		// /finances/transaction-category/store
		Route::post('store', '\\' . TransactionCategoryController::class . '@actionStore')
			 ->name('finances.transaction-category.store');
	});
});