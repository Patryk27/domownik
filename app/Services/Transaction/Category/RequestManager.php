<?php

namespace App\Services\Transaction\Category;

use App\Http\Requests\TransactionCategory\StoreRequest as TransactionCategoryStoreRequest;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Services\Transaction\Category\RequestManager\CategoryDeleter;
use App\Services\Transaction\Category\RequestManager\CategoryUpdater;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestManager
	implements RequestManagerContract {

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
	public function store(TransactionCategoryStoreRequest $request): RequestManagerContract {
		$this->log->info('Updating transaction category list: %s.', $request);

		$this->db->transaction(function() use ($request) {
			$categoryUpdater = new CategoryUpdater();
			$categoryUpdater->updateCategories($request->get('newTree'));

			$categoryDeleter = new CategoryDeleter($this->transactionCategoryRepository);
			$categoryDeleter->deleteCategories($request->get('deletedNodeIds', []));

			TransactionCategory::getFlushCache()
							   ->flush();

			Transaction::getFlushCache()
					   ->flush();
		});

		return $this;
	}

}