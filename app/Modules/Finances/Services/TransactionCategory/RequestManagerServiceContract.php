<?php

namespace App\Modules\Finances\Services\TransactionCategory;

use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest;

// @todo remove the 'service' from the interface name
interface RequestManagerServiceContract {

	/**
	 * @param StoreRequest $request
	 * @return RequestManagerServiceContract
	 */
	public function store(StoreRequest $request): self;

}