<?php

namespace App\Modules\Finances\Services\TransactionCategory;

use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest;

interface RequestManagerServiceContract {

	/**
	 * @param StoreRequest $request
	 * @return RequestManagerServiceContract
	 */
	public function store(StoreRequest $request): self;

}