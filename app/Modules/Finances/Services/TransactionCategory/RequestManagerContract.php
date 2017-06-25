<?php

namespace App\Modules\Finances\Services\TransactionCategory;

use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest;

interface RequestManagerContract {

	/**
	 * @param StoreRequest $request
	 * @return RequestManagerContract
	 */
	public function store(StoreRequest $request): self;

}