<?php

namespace App\Repositories\Contracts;

interface SettingsRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueByKey($key);

	/**
	 * @param int|null $userId
	 * @param string $key
	 * @return mixed|null
	 */
	public function getUserValueByKey($userId, $key);

}