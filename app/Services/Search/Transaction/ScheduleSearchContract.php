<?php

namespace App\Services\Search\Transaction;

use App\Services\Search\SearchContract;

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
	public function parentTypeAndId(string $parentType, int $parentId);

	/**
	 * @param string $operator
	 * @param $date
	 * @return $this
	 */
	public function date(string $operator, $date);

}