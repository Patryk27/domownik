<?php

namespace App\Repositories\Contracts;

use App\Models\Module;

interface ModuleRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * Returns module with given name or `null`.
	 * @param string $moduleName
	 * @return Module|null
	 */
	public function getByName(string $moduleName);

}