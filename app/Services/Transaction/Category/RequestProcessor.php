<?php

namespace App\Services\Transaction\Category;

use App\Http\Requests\Transaction\Category\StoreRequest as TransactionCategoryStoreRequest;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Services\Transaction\Category\RequestProcessor\CategoryDeleter;
use App\Services\Transaction\Category\RequestProcessor\CategoryUpdater;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var CategoryDeleter
	 */
	protected $categoryDeleter;

	/**
	 * @var CategoryUpdater
	 */
	protected $categoryUpdater;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 * @param CategoryDeleter $categoryDeleter
	 * @param CategoryUpdater $categoryUpdater
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionCategoryRepositoryContract $transactionCategoryRepository,
		CategoryDeleter $categoryDeleter,
		CategoryUpdater $categoryUpdater
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
		$this->categoryDeleter = $categoryDeleter;
		$this->categoryUpdater = $categoryUpdater;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionCategoryStoreRequest $request): void {
		$this->db->transaction(function () use ($request) {
			$this->categoryUpdater->updateCategories($request->get('newTree'));
			$this->categoryDeleter->deleteCategories($request->get('deletedNodeIds', []));

			$this->transactionCategoryRepository
				->getFlushCache()
				->flush();
		});
	}

}