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
	protected $creator;

	/**
	 * @var BudgetUpdateRequestProcessor
	 */
	protected $updater;

	/**
	 * @param BudgetStoreRequestProcessor $creator
	 * @param BudgetUpdateRequestProcessor $updater
	 */
	public function __construct(
		BudgetStoreRequestProcessor $creator,
		BudgetUpdateRequestProcessor $updater
	) {
		$this->creator = $creator;
		$this->updater = $updater;
	}

	/**
	 * @inheritDoc
	 */
	public function store(BudgetStoreRequest $request): BudgetStoreResult {
		return $this->creator->process($request);
	}

	/**
	 * @inheritDoc
	 */
	public function update(BudgetUpdateRequest $request, int $id): BudgetUpdateResult {
		return $this->updater->process($request, $id);
	}

}