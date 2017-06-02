<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;

/**
 * @var Setting $model
 */
class SettingsRepository
	extends AbstractCrudRepository
	implements \App\Repositories\Contracts\SettingsRepositoryContract {

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getValueByKey($key) {
		return $this->getUserValueByKey(null, $key);
	}

	/**
	 * @param int $userId
	 * @param string $key
	 * @return mixed
	 */
	public function getUserValueByKey($userId, $key) {
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
	protected function getModelName() {
		return Setting::class;
	}

}