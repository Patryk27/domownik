<?php

namespace App\Support\Classes;

use Carbon\Carbon;

class Date {

	/**
	 * @param string $format
	 * @param string|Carbon $date
	 * @return string
	 */
	public function format(string $format, $date): string {
		if (is_string($date)) {
			$date = new Carbon($date);
		}

		/**
		 * @var Carbon $date
		 */

		$date = $date->copy();
		$date->setTimezone('Europe/Warsaw'); // @todo shouldn't be hardcoded

		$formatted = $date->formatLocalized($format);

		/**
		 * Windows's locales are always in Windows-1250 code page, whereas we expect to
		 * process only UTF-8 strings.
		 */
		if (\Utils::getOperatingSystem() === Utils::OS_WINDOWS) {
			$formatted = iconv('windows-1250', 'utf-8', $formatted);
		}

		return $formatted;
	}

	/**
	 * Strips (sets to zero) time of given Carbon date.
	 * Does not modify given parameter - returns a new, modified date instead.
	 * @param \Carbon\Carbon|null $date
	 * @return \Carbon\Carbon|null
	 */
	public function stripTime($date) {
		if (isset($date) && $date instanceof Carbon) {
			$date = $date->copy();
			$date->setTime(0, 0, 0);
		}

		return $date;
	}

}