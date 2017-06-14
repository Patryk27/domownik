<?php

namespace App\Support;

use Carbon\Carbon;

trait UsesCache {

	/**
	 * Returns cache key name of given function call.
	 * Accepts any $methodArgs.
	 * @param string $functionName
	 * @param array $methodArgs
	 * @return string
	 */
	protected function getCacheKey($functionName, array $methodArgs = []): string {
		$className = str_replace('\\', '.', get_class($this));
		$cacheKey = sprintf('%s:%s', $className, $functionName);

		if (!empty($methodArgs)) {
			$methodArgsString = implode(',', array_map(function($methodArg) {
				/**
				 * We use Redis and thus, in order to simplify debugging, we do our best effort to make sure the cache
				 * key human-readable.
				 */

				if (is_null($methodArg)) {
					return 'null';
				}

				if (is_int($methodArg)) {
					// integer=
					return 'int=' . $methodArg;
				}

				if (is_float($methodArg)) {
					// float=
					return 'flt=' . $methodArg;
				}

				if (is_string($methodArg)) {
					// string=
					return 'str=' . base64_encode($methodArg);
				}

				if (is_array($methodArg)) {
					// array=
					return 'arr=' . base64_encode(serialize($methodArg));
				}

				if (is_object($methodArg)) {
					if ($methodArg instanceof Carbon) {
						return 'obj.carbon=' . $methodArg->toIso8601String();
					}

					// object=
					return 'obj= ' . base64_encode(serialize($methodArg));
				}

				// other=
				return 'oth=' . base64_encode(serialize($methodArg));
			}, $methodArgs));

			$cacheKey .= ':' . $methodArgsString;
		}

		return $cacheKey;
	}

}