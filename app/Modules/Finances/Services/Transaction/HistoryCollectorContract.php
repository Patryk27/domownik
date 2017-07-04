<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Modules\Finances\Models\Transaction;
use App\ServiceContracts\BasicSearchContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface HistoryCollectorContract
	extends BasicSearchContract {

	const
		GROUP_MODE_DAILY = 'daily',
		GROUP_MODE_WEEKLY = 'weekly',
		GROUP_MODE_MONTHLY = 'monthly',
		GROUP_MODE_YEARLY = 'yearly';

	const
		SORT_DIRECTION_ASCENDING = 'asc',
		SORT_DIRECTION_DESCENDING = 'desc';

	/**
	 * Returns a list of transactions of which every has the 'periodicity' field set to according (one shot) value.
	 * @return Collection|Transaction[]
	 */
	public function getRows(): Collection;

	/**
	 * Returns simple rows prepared for the charts.
	 * @return array
	 */
	public function getRowsForChart(): array;

	/**
	 * @return string
	 */
	public function getParentType(): string;

	/**
	 * @param string $parentType
	 * @return HistoryCollectorContract
	 */
	public function setParentType(string $parentType): HistoryCollectorContract;

	/**
	 * @return int
	 */
	public function getParentId(): int;

	/**
	 * @param int $parentId
	 * @return HistoryCollectorContract
	 */
	public function setParentId(int $parentId): HistoryCollectorContract;

	/**
	 * @return Carbon|null
	 */
	public function getDateFrom();

	/**
	 * @param Carbon|null $dateFrom
	 * @return HistoryCollectorContract
	 */
	public function setDateFrom($dateFrom): HistoryCollectorContract;

	/**
	 * @return Carbon|null
	 */
	public function getDateTo();

	/**
	 * @param Carbon|null $dateTo
	 * @return HistoryCollectorContract
	 */
	public function setDateTo($dateTo): HistoryCollectorContract;

	/**
	 * @return string
	 */
	public function getSortDirection(): string;

	/**
	 * @param string $sortDirection
	 * @return HistoryCollectorContract
	 */
	public function setSortDirection(string $sortDirection): HistoryCollectorContract;

}