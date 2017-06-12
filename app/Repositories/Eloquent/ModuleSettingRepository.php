<?php

namespace App\Repositories\Eloquent;

use App\Models\ModuleSetting;
use App\Repositories\Contracts\ModuleSettingRepositoryContract;

/**
 * @var ModuleSetting $model
 */
class ModuleSettingRepository
	extends AbstractCrudRepository
	implements ModuleSettingRepositoryContract {

	/**
	 * @inheritDoc
	 */
	public function getValueByKey(int $moduleId, string $key) {
		// @todo cache

		$row =
			ModuleSetting
				::where('module_id', $moduleId)
				->where('key', $key)
				->first();

		if (empty($row)) {
			return null;
		}

		return $row->value;
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName() {
		return ModuleSetting::class;
	}

}