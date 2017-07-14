<?php

namespace App\Services\Section;

interface ManagerContract {

	/**
	 * Returns names of all application's sections.
	 * @return string[]
	 */
	public function getSectionNames(): array;

}