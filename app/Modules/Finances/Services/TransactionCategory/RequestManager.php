<?php

namespace App\Modules\Finances\Services\TransactionCategory;

use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest as TransactionCategoryStoreRequest;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionCategory;
use App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Modules\Finances\Services\TransactionCategory\RequestManager\CategoryDeleter;
use App\Modules\Finances\Services\TransactionCategory\RequestManager\CategoryUpdater;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection;

class RequestManager
	implements RequestManagerContract {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @param LoggerContract $logger
	 * @param Connection $databaseConnection
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		LoggerContract $logger,
		Connection $databaseConnection,
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->logger = $logger;
		$this->databaseConnection = $databaseConnection;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionCategoryStoreRequest $request): RequestManagerContract {
		$this->logger->info('Updating transaction category list: %s.', $request);

		$this->databaseConnection->transaction(function() use ($request) {
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