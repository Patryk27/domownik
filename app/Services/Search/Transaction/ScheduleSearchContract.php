<?php

namespace App\Services\Search\Transaction;

use App\Services\Search\SearchContract;
use Carbon\Carbon;

interface ScheduleSearchContract
	extends SearchContract {

	const
		ORDER_DATE = 'ts.date',
		TRANSACTION_ID = 'ts.id';

	/**
	 * @param string $parentType
	 * @param int $parentId
	 * @return $this
	 */
	public function parent(string $parentType, int $parentId);

	/**
	 * @param string $operator
	 * @param Carbon|string $date
	 * @return $this
	 */
	public function date(string $operator, $date);

	/**
	 * @param string $name
	 * @return $this
	 */
	public function name(string $name);

}