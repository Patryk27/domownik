<?php

namespace App\Repositories\Contracts;

interface SettingRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueByKey(string $key);

	/**
	 * @param int|null $userId
	 * @param string $key
	 * @return mixed|null
	 */
	public function getUserValueByKey($userId, string $key);

}