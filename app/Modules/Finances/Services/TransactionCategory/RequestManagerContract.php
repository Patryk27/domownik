<?php

namespace App\Modules\Finances\Services\TransactionCategory;

use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest as TransactionCategoryStoreRequest;

interface RequestManagerContract {

	/**
	 * @param TransactionCategoryStoreRequest $request
	 * @return RequestManagerContract
	 */
	public function store(TransactionCategoryStoreRequest $request): self;

}