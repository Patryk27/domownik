<?php

namespace App\Services\Budget\Request;

use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\Services\Budget\Request\Processor\Store as BudgetStoreRequestProcessor;
use App\Services\Budget\Request\Processor\Update as BudgetUpdateRequestProcessor;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;
use App\ValueObjects\Requests\Budget\UpdateResult as BudgetUpdateResult;

class Processor
	implements ProcessorContract {

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