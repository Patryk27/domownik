<?php

namespace App\Services\Search\Transaction;

use App\Models\Transaction;
use App\Services\Search\SearchContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface OneShotSearchContract
	extends SearchContract {

	const TRANSACTION_DATE = 'tpos.date';

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

	/**
	 * Returns all the transactions.
	 * @return Collection|Transaction[]
	 */
	public function get(): Collection;

	/**
	 * Returns all the transactions prepared for chart.
	 * @return array
	 * @todo user some mapper/adapter pattern
	 */
	public function getChart(): array;

}