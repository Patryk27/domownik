<?php

namespace App\Services\Install;

interface ManagerContract {

	/**
	 * Returns true/false depending whether the application is installed or not.
	 * @return bool
	 */
	public function isApplicationInstalled(): bool;

}