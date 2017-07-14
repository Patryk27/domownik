<?php

namespace App\Services\Transaction\Category;

use App\Http\Requests\TransactionCategory\StoreRequest as TransactionCategoryStoreRequest;

interface RequestManagerContract {

	/**
	 * @param TransactionCategoryStoreRequest $request
	 * @return RequestManagerContract
	 */
	public function store(TransactionCategoryStoreRequest $request): self;

}