<?php

namespace App\Repositories\Contracts;

interface ModuleSettingRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @param int $moduleId
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueByKey(int $moduleId, string $key);

}