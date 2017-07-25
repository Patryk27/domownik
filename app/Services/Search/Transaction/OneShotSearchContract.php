<?php

namespace App\Services\Search\Transaction;

use App\Models\Transaction;
use App\Services\Search\SearchContract;
use Illuminate\Support\Collection;

interface OneShotSearchContract
	extends SearchContract {

	const ORDER_DATE = 'tpos.date';

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

	/**
	 * @return Collection|Transaction[]
	 */
	public function get(): Collection;

	/**
	 * @return array
	 */
	public function getChart(): array;

}