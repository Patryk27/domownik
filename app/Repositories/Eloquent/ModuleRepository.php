<?php

namespace App\Repositories\Eloquent;

use App\Models\Module;

/**
 * @var Module $model
 */
class ModuleRepository
	extends AbstractCrudRepository
	implements \App\Repositories\Contracts\ModuleRepositoryContract {

	/**
	 * @inheritDoc
	 */
	public function getByName(string $moduleName) {
		// @todo cache

		return
			Module::where('name', $moduleName)
				  ->first();
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName() {
		return Module::class;
	}

}