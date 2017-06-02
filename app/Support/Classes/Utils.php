<?php

namespace App\Support\Classes;

class Utils {

	const
		OS_WINDOWS = 'os-windows',
		OS_LINUX = 'os-linux';

	/**
	 * @return string
	 */
	public function getOperatingSystem() {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			return self::OS_WINDOWS;
		} else {
			return self::OS_LINUX;
		}
	}

}