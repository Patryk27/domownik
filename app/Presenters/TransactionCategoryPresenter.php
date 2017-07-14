<?php

namespace App\Presenters;

use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;

class TransactionCategoryPresenter
	extends AbstractPresenter {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var TransactionCategory
	 */
	protected $model;

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
	public function getFullName() {
		if ($this->model->offsetExists('full_name')) {
			return $this->model->full_name;
		}

		$fullName = $this->transactionCategoryRepository->getFullName($this->model->id);
		$this->model->full_name = $fullName;

		return $fullName;
	}

}