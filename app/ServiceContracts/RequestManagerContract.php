<?php

namespace App\ServiceContracts;

use App\Models\Model;
use Illuminate\Http\Request;

interface RequestManagerContract {

	const
		STORE_RESULT_CREATED = 'store-result-created',
		STORE_RESULT_UPDATED = 'store-result-updated';

	/**
	 * Inserts or updates model using given request data.
	 * Returns @see STORE_RESULT_CREATED or @see STORE_RESULT_UPDATED, depending on what was done.
	 * @param Request $request
	 * @return string
	 */
	public function store($request): string;

	/**
	 * Returns stored model.
	 * Can be called after @see store is done.
	 * @return Model
	 */
	public function getModel();

}