<?php

namespace App\Services\Search\Transaction;

use App\Services\Search\SearchContract;
use Carbon\Carbon;

interface ScheduleSearchContract
	extends SearchContract {

	const
		TRANSACTION_DATE = 'ts.date',
		TRANSACTION_ID = 'ts.id';

	/**
	 * Filters data by transaction's parent type, eg.: 'budget, 1'.
	 * @param string $parentType
	 * @param int $parentId
	 * @return $this
	 */
	public function parent(string $parentType, int $parentId);

	/**
	 * Filters data by transaction's periodicity date, eg.: '>= 2017-01-01'.
	 * @param string $operator
	 * @param Carbon|string $date
	 * @return $this
	 */
	public function date(string $operator, $date);

	/**
	 * Filters data by transaction's name, eg.: 'test'.
	 * @param string $name
	 * @return $this
	 */
	public function name(string $name);

}