<?php

namespace App\Services\Transaction\Category;

use App\Http\Requests\Transaction\Category\StoreRequest as TransactionCategoryStoreRequest;

interface RequestProcessorContract {

	/**
	 * @param TransactionCategoryStoreRequest $request
	 * @return void
	 */
	public function store(TransactionCategoryStoreRequest $request): void;

}