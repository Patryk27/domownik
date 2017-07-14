<?php

namespace App\Repositories\Contracts;

use App\Models\TransactionSchedule;
use App\ValueObjects\ScheduledTransaction;

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

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return TransactionSchedule|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return TransactionSchedule
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|TransactionSchedule[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|TransactionSchedule[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}