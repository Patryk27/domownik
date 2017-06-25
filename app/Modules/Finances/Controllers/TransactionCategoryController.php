<?php

namespace App\Modules\Finances\Controllers;

use App\Exceptions\Exception;
use App\Http\Controllers\Controller;
use App\Modules\Finances\Http\Requests\TransactionCategory\StoreRequest;
use App\Modules\Finances\Models\TransactionCategory;
use App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Services\TransactionCategory\RequestManagerContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TransactionCategoryController
	extends Controller {

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
	 * @var RequestManagerContract
	 */
	protected $transactionCategoryRequestManagerService;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		TransactionRepositoryContract $transactionRepository,
		TransactionCategoryRepositoryContract $transactionCategoryRepository,
		RequestManagerContract $requestManagerService
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->transactionRepository = $transactionRepository;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
		$this->transactionCategoryRequestManagerService = $requestManagerService;
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionList(Request $request) {
		if ($request->ajax()) {
			$transactionCategories = $this->transactionCategoryRepository->getMainCategories();
			$tree = $this->prepareTransactionCategoryTree($transactionCategories);

			return response()->json($tree);
		}

		$this->breadcrumbManager
			->push(null, __('Finances::breadcrumb.transaction-category.list'));

		return view('Finances::transaction-category.list');
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionStore(StoreRequest $request) {
		try {
			$this->transactionCategoryRequestManagerService->store($request);

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

	/**
	 * @param Collection|TransactionCategory[] $transactionCategories
	 * @return array
	 */
	protected function prepareTransactionCategoryTree(Collection $transactionCategories): array {
		$result = [];

		foreach ($transactionCategories as $transactionCategory) {
			$categorySubcategories = $this->transactionCategoryRepository->getSubcategories($transactionCategory->id);

			$result[] = [
				'id' => $transactionCategory->id,
				'text' => $transactionCategory->name,
				'children' => $this->prepareTransactionCategoryTree($categorySubcategories),
			];
		}

		return $result;
	}

}