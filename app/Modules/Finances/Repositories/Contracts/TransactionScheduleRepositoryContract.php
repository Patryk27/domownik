<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\ValueObjects\ScheduledTransaction;
use App\Repositories\Contracts\CrudRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface TransactionScheduleRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * Returns all transactions schedule to be booked to given date (including this date).
	 * @param Carbon $date
	 * @return Collection|ScheduledTransaction[]
	 */
	public function getToDate(Carbon $date): Collection;

	/**
	 * Returns all transactions scheduled to be booked between given dates.
	 * The collection is sorted ascending by the dates.
	 * @param int $budgetId
	 * @param Carbon $dateFrom
	 * @param Carbon $dateTo
	 * @return Collection|ScheduledTransaction[]
	 */
	public function getByBudgetId(int $budgetId, Carbon $dateFrom, Carbon $dateTo): Collection;

	/**
	 * Deletes all transaction schedules for given transaction.
	 * @param int $transactionId
	 * @return TransactionScheduleRepositoryContract
	 */
	public function deleteByTransactionId(int $transactionId): TransactionScheduleRepositoryContract;

}