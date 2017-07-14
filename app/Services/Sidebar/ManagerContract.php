<?php

namespace App\Services\Sidebar;

interface ManagerContract {

	/**
	 * Returns all the sidebars.
	 * @return array
	 */
	public function getSidebars(): array;

}