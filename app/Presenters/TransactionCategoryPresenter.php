<?php

namespace App\Presenters;

use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;

/**
 * @property TransactionCategory $model
 */
class TransactionCategoryPresenter
	extends AbstractPresenter {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * Returns category name with its path.
	 * Eg.: 'Hello -> World'.
	 * @return string
	 */
	public function getFullName(): string{
		if ($this->model->offsetExists('full_name')) {
			return $this->model->full_name;
		}

		return $this->model->full_name = $this->transactionCategoryRepository->getFullName($this->model->id);
	}

}