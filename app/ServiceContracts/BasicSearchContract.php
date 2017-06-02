<?php

namespace App\ServiceContracts;

use Illuminate\Support\Collection;

interface BasicSearchContract {

	/**
	 * Resets all the used filters to their default values.
	 * @return $this
	 */
	public function reset(): BasicSearchContract;

	/**
	 * Returns all rows matching given criteria.
	 * @return Collection
	 */
	public function getRows(): Collection;

}