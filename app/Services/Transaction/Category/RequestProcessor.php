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
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionCategoryStoreRequest $request): void {
		$this->log->info('Updating transaction category list: %s.', $request);

		$this->db->transaction(function () use ($request) {
			$categoryUpdater = new CategoryUpdater();
			$categoryUpdater->updateCategories($request->get('newTree'));

			$categoryDeleter = new CategoryDeleter($this->transactionCategoryRepository);
			$categoryDeleter->deleteCategories($request->get('deletedNodeIds', []));

			$this->transactionCategoryRepository
				->getFlushCache()
				->flush();
		});
	}

}