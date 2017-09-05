<?php

namespace App\Services\Asset;

interface CompilerContract {

	/**
	 * Compiles all the assets.
	 * @return $this
	 */
	public function compileAssets();

}