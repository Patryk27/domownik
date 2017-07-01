<?php

namespace App\Filesystem\Memory;

class Helper {

	/**
	 * @param string $path
	 * @return array
	 */
	public static function parsePath(string $path): array {
		return explode('/', $path);
	}

	/**
	 * @param string|array $path
	 * @return string
	 */
	public static function pathToString($path): string {
		if (is_array($path)) {
			$path = implode('/', $path);
		}

		return $path;
	}

}