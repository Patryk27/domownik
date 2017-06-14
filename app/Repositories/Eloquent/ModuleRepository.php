<?php

namespace App\Repositories\Eloquent;

use App\Models\Module;
use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Support\UsesCache;

/**
 * @var Module $model
 */
class ModuleRepository
	extends AbstractCrudRepository
	implements ModuleRepositoryContract {

	use UsesCache;

	/**
	 * @inheritDoc
	 */
	public function getByName(string $moduleName) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($moduleName) {
			return
				Module::where('name', $moduleName)
					  ->first();
		});
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName() {
		return Module::class;
	}

}