<?php

namespace App\Http\Controllers\Finances;

use App\Exceptions\Exception;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Transaction\Category\StoreRequest as TransactionCategoryStoreRequest;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Transaction\Category\Request\ProcessorContract as TransactionCategoryRequestProcessorContract;
use App\Services\Transaction\Category\TransformatorContract as TransactionCategoryTransformatorContract;
use Illuminate\Http\Request;

class TransactionCategoriesController
	extends BaseController {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var TransactionCategoryTransformatorContract
	 */
	protected $transactionCategoryTransformator;

	/**
	 * @var TransactionCategoryRequestProcessorContract
	 */
	protected $transactionCategoryRequestProcessor;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 * @param TransactionCategoryTransformatorContract $transactionCategoryTransformator
	 * @param TransactionCategoryRequestProcessorContract $transactionCategoryRequestProcessor
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		TransactionRepositoryContract $transactionRepository,
		TransactionCategoryRepositoryContract $transactionCategoryRepository,
		TransactionCategoryTransformatorContract $transactionCategoryTransformator,
		TransactionCategoryRequestProcessorContract $transactionCategoryRequestProcessor
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->transactionRepository = $transactionRepository;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
		$this->transactionCategoryTransformator = $transactionCategoryTransformator;
		$this->transactionCategoryRequestProcessor = $transactionCategoryRequestProcessor;
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function index(Request $request) {
		if ($request->ajax()) {
			$categories = $this->transactionCategoryRepository->getMainCategories();
			$categoryTree = $this->transactionCategoryTransformator->convertListToTree($categories);
			$categoryJsTree = $this->transactionCategoryTransformator->convertTreeToJsTree($categoryTree);

			return response()->json($categoryJsTree);
		}

		$this->breadcrumbManager->push(route('finances.transaction-categories.index'), __('breadcrumbs.transaction-categories.index'));

		return view('views.finances.transaction-categories.index', [
			'form' => [
				'url' => route('finances.transaction-categories.store'),
				'method' => 'put',
			],
		]);
	}

	/**
	 * @param TransactionCategoryStoreRequest $request
	 * @return mixed
	 */
	public function store(TransactionCategoryStoreRequest $request) {
		try {
			$this->transactionCategoryRequestProcessor->store($request);

			return response()->json([
				'success' => true,
			]);
		} catch (Exception $ex) {
			return response()->json([
				'success' => false,
				'message' => $ex->getMessage(),
			]);
		}
	}

}