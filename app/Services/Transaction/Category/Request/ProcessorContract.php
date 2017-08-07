<?php

namespace App\Services\Transaction\Category\Request;

use App\Http\Requests\Transaction\Category\StoreRequest as TransactionCategoryStoreRequest;

interface ProcessorContract {

	/**
	 * @param TransactionCategoryStoreRequest $request
	 * @return void
	 */
	public function store(TransactionCategoryStoreRequest $request): void;

}