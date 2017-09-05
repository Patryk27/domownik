<?php

namespace App\Services\Configuration;

use App\Exceptions\ConfigurationException;
use App\Repositories\Contracts\SettingRepositoryContract;
use Illuminate\Auth\AuthManager;

class Manager {

	/**
	 * @var AuthManager
	 */
	protected $authManager;

	/**
	 * @var SettingRepositoryContract
	 */
	protected $settingsRepository;

	/**
	 * @param AuthManager $authManager
	 * @param SettingRepositoryContract $settingsRepository
	 */
	public function __construct(
		AuthManager $authManager,
		SettingRepositoryContract $settingsRepository
	) {
		$this->authManager = $authManager;
		$this->settingsRepository = $settingsRepository;
	}

	/**
	 * Returns given configuration unserialized option value or throws exception if option was not found.
	 * @param string $key
	 * @return mixed
	 * @throws ConfigurationException
	 */
	public function getValueOrFail(string $key) {
		$value = $this->getValueOrNull($key);

		if (is_null($value)) {
			throw new ConfigurationException('Configuration key not found: %s.', $key);
		}

		return $value;
	}

	/**
	 * Returns given configuration unserialized option value or `null` if option was not found.
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueOrNull(string $key) {
		$guard = $this->authManager->guard();

		// check user preferences
		if ($guard->check()) {
			$value = $this->settingsRepository->getUserValueByKey($guard->id(), $key);

			if (!is_null($value)) {
				return $value;
			}
		}

		// check global settings
		return $this->settingsRepository->getValueByKey($key);
	}

	/**
	 * Returns given configuration unserialized option value or default value if option was not found.
	 * @param string $key
	 * @return mixed
	 */
	public function getValueOrDefault(string $key) {
		$value = $this->getValueOrNull($key);

		if (is_null($value)) {
			return $this->getDefaultValue($key);
		} else {
			return $value;
		}
	}

	/**
	 * Returns option's default value.
	 * @param string $key
	 * @return mixed
	 */
	public function getDefaultValue(string $key) {
		// @todo

		if ($key === 'language') {
			// @todo use DI or something else
			if (app()->environment() === 'testing') {
				return 'testing';
			}

			return 'pl';
		}

		return 'asdf';
	}

}
