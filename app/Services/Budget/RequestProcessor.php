<?php

namespace App\Services\Budget;

use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\Services\Budget\RequestProcessor\Store as BudgetStoreRequestProcessor;
use App\Services\Budget\RequestProcessor\Update as BudgetUpdateRequestProcessor;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;
use App\ValueObjects\Requests\Budget\UpdateResult as BudgetUpdateResult;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var BudgetStoreRequestProcessor
	 */
	protected $storeRequestProcessor;

	/**
	 * @var BudgetUpdateRequestProcessor
	 */
	protected $updateRequestProcessor;

	/**
	 * @param BudgetStoreRequestProcessor $creator
	 * @param BudgetUpdateRequestProcessor $updater
	 */
	public function __construct(
		BudgetStoreRequestProcessor $creator,
		BudgetUpdateRequestProcessor $updater
	) {
		$this->storeRequestProcessor = $creator;
		$this->updateRequestProcessor = $updater;
	}

	/**
	 * @inheritDoc
	 */
	public function store(BudgetStoreRequest $request): BudgetStoreResult {
		return $this->storeRequestProcessor->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(BudgetUpdateRequest $request, int $id): BudgetUpdateResult {
		return $this->updateRequestProcessor->process($request, $id);
	}

}