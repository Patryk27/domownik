<?php

namespace App\Http\Controllers\Finances\Budgets;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Budget\Transaction\SearchBookedRequest as SearchBookedTransactionRequest;
use App\Http\Requests\Budget\Transaction\SearchScheduledRequest as SearchScheduledTransactionRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Search\Transaction\OneShotSearchContract as OneShotTransactionSearchContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use Carbon\Carbon;

class TransactionsController
	extends BaseController {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var OneShotTransactionSearchContract
	 */
	protected $oneShotTransactionSearch;

	/**
	 * @var TransactionScheduleSearchContract
	 */
	protected $transactionScheduleSearch;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param OneShotTransactionSearchContract $oneShotTransactionSearch
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		OneShotTransactionSearchContract $oneShotTransactionSearch,
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->oneShotTransactionSearch = $oneShotTransactionSearch;
		$this->transactionScheduleSearch = $transactionScheduleSearch;
	}

	/**
	 * @param Budget $budget
	 * @param SearchBookedTransactionRequest $request
	 * @return mixed
	 */
	public function booked(Budget $budget, SearchBookedTransactionRequest $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budgets.transactions.booked', $budget->id), __('breadcrumbs.budgets.transactions.booked'));

		$this->oneShotTransactionSearch->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id);

		// apply filters
		if ($request->has('dateFrom')) {
			$this->oneShotTransactionSearch->date('>=', new Carbon($request->get('dateFrom')));
		}

		if ($request->has('dateTo')) {
			$this->oneShotTransactionSearch->date('<=', new Carbon($request->get('dateTo')));
		} else {
			$this->oneShotTransactionSearch->date('<=', new Carbon());
		}

		if ($request->has('name')) {
			$this->oneShotTransactionSearch->name($request->get('name'));
		}

		// apply limit
		$this->oneShotTransactionSearch
			->getQueryBuilder()
			->orderBy(OneShotTransactionSearchContract::TRANSACTION_DATE, 'desc')
			->limit($request->get('limit', 100));

		// fetch data
		$transactions = $this->oneShotTransactionSearch->get();

		return view('views.finances.budgets.transactions.index', [
			'budget' => $budget,
			'transactions' => $transactions,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param SearchScheduledTransactionRequest $request
	 * @return mixed
	 */
	public function scheduled(Budget $budget, SearchScheduledTransactionRequest $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budgets.transactions.scheduled', $budget->id), __('breadcrumbs.budgets.transactions.scheduled'));

		$this->transactionScheduleSearch->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id);

		// apply filters
		if ($request->has('dateFrom')) {
			$this->transactionScheduleSearch->date('>=', new Carbon($request->get('dateFrom')));
		} else {
			$this->transactionScheduleSearch->date('>=', new Carbon());
		}

		if ($request->has('dateTo')) {
			$this->transactionScheduleSearch->date('<=', new Carbon($request->get('dateTo')));
		}

		if ($request->has('name')) {
			$this->transactionScheduleSearch->name($request->get('name'));
		}

		// apply limit
		$this->transactionScheduleSearch
			->getQueryBuilder()
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_DATE, 'asc')
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_ID, 'asc')
			->limit($request->get('limit', 100));

		$transactions = $this->transactionScheduleSearch->get();

		return view('views.finances.budgets.transactions.index', [
			'budget' => $budget,
			'transactions' => $transactions,
		]);
	}

}