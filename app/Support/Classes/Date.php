<?php

namespace App\Support\Classes;

use Carbon\Carbon;

class Date {

	/**
	 * @param string $format
	 * @param string|Carbon $date
	 * @return string
	 */
	public function format($format, $date) {
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

}