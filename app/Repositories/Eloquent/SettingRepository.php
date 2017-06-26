<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryContract;

/**
 * @var Setting $model
 */
class SettingRepository
	extends AbstractCrudRepository
	implements SettingRepositoryContract {

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getValueByKey(string $key) {
		return $this->getUserValueByKey(null, $key);
	}

	/**
	 * @param int $userId
	 * @param string $key
	 * @return mixed
	 */
	public function getUserValueByKey($userId, string $key) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($userId, $key) {
			$model = $this->model
				->where('user_id', '=', $userId)
				->where('key', '=', $key)
				->get()
				->first();

			if (is_null($model)) {
				return null;
			} else {
				return $model->value;
			}
		});
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return Setting::class;
	}

}