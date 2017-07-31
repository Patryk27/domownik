<?php

namespace App\Repositories\Contracts;

use App\Models\TransactionSchedule;
use Illuminate\Support\Collection;

interface TransactionScheduleRepositoryContract
	extends CrudRepositoryContract {

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