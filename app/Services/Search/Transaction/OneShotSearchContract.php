<?php

namespace App\Services\Search\Transaction;

use App\Models\Transaction;
use App\Services\Search\SearchContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface OneShotSearchContract
	extends SearchContract {

	const ORDER_DATE = 'tpos.date';

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

	/**
	 * @return Collection|Transaction[]
	 */
	public function get(): Collection;

	/**
	 * @return array
	 */
	public function getChart(): array;

}