<?php

namespace App\Support\Classes;

use Carbon\Carbon;

class Calendar {

	/**
	 * Week days according to the ISO 8601.
	 */
	public const
		MONDAY = 1,
		TUESDAY = 2,
		WEDNESDAY = 3,
		THURSDAY = 4,
		FRIDAY = 5,
		SATURDAY = 6,
		SUNDAY = 7;

	/**
	 * Map [ week day id => week day name ].[
	 * @var string[]
	 */
	protected $weekdays;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// @todo week days order should depend on selected user's calendar

		$this->weekdays = [
			self::MONDAY => 'Monday',
			self::TUESDAY => 'Tuesday',
			self::WEDNESDAY => 'Wednesday',
			self::THURSDAY => 'Thursday',
			self::FRIDAY => 'Friday',
			self::SATURDAY => 'Saturday',
			self::SUNDAY => 'Sunday',
		];
	}

	/**
	 * Returns week days, in correct (depending on the calendar) order.
	 * @return string[]
	 */
	public function getWeekdaysCapitalized(): array {
		return $this->weekdays;
	}

	/**
	 * Returns week days, in correct (depending on the calendar) order.
	 * @return string[]
	 * @see getWeekdaysCapitalized()
	 */
	public function getWeekdays(): array {
		return array_map('strtolower', self::getWeekdaysCapitalized());
	}

	/**
	 * Returns mapping of weekdays between Carbon and Calendar.
	 * This is required everywhere we use Carbon's "dayOfWeek", because Carbon is not
	 * ISO 8601-compatible when processing weekdays and our application has to be.
	 * @return array
	 */
	public function getCarbonWeekdaysMapping(): array {
		return [
			Carbon::MONDAY => self::MONDAY,
			Carbon::TUESDAY => self::TUESDAY,
			Carbon::WEDNESDAY => self::WEDNESDAY,
			Carbon::THURSDAY => self::THURSDAY,
			Carbon::FRIDAY => self::FRIDAY,
			Carbon::SATURDAY => self::SATURDAY,
			Carbon::SUNDAY => self::SUNDAY,
		];
	}

	/**
	 * Returns map [ year => year ].
	 * @param int|null $startingYear
	 * @param int|null $endingYear
	 * @return array
	 */
	public function getYearsMap(?int $startingYear, ?int $endingYear): array {
		if (is_null($startingYear)) {
			$startingYear = (int)date('Y');
		}

		if (is_null($endingYear)) {
			$endingYear = (int)date('Y');
		}

		$result = [];

		for ($year = $startingYear; $year <= $endingYear; ++$year) {
			$result[$year] = $year;
		}

		return $result;
	}

	/**
	 * Returns map [ month-id => month-name ], eg.: [ 1 => January, 2 => February, ... ].
	 * @return array
	 */
	public function getMonthsMap(): array {
		$result = [];

		for ($monthId = 1; $monthId <= 12; ++$monthId) {
			$result[$monthId] = __('calendar.months')[$monthId];
		}

		return $result;
	}

}