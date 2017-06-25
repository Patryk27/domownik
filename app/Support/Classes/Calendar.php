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
	public function getWeekdaysCapitalized() {
		return $this->weekdays;
	}

	/**
	 * Returns week days, in correct (depending on the calendar) order.
	 * @return string[]
	 * @see getWeekdaysCapitalized()
	 */
	public function getWeekdays() {
		return array_map('strtolower', self::getWeekdaysCapitalized());
	}

	/**
	 * Returns mapping of weekdays between Carbon and Calendar.
	 * This is required everywhere we use Carbon's "dayOfWeek", because Carbon is not
	 * ISO 8601-compatible when processing weekdays and our application has to be.
	 * @return array
	 */
	public function getCarbonWeekdaysMapping() {
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

}