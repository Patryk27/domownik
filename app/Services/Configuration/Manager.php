<?php

namespace App\Services\Configuration;

use App\Exceptions\ConfigurationException;
use App\Repositories\Contracts\SettingRepositoryContract;
use Illuminate\Support\Facades\Auth;

class Manager {

	/**
	 * @var SettingRepositoryContract
	 */
	protected $settingsRepository;

	/**
	 * @param SettingRepositoryContract $settingsRepository
	 */
	public function __construct(SettingRepositoryContract $settingsRepository) {
		$this->settingsRepository = $settingsRepository;
	}

	/**
	 * Returns given configuration unserialized option value or `null` if option was not found.
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueOrNull($key) {
		// check user preferences
		if (Auth::check()) {
			$value = $this->settingsRepository->getUserValueByKey(Auth::id(), $key);

			if (!is_null($value)) {
				return $value;
			}
		}

		// check global settings
		return $this->settingsRepository->getValueByKey($key);
	}

	/**
	 * Returns given configuration unserialized option value or throws exception if option was not found.
	 * @param string $key
	 * @return mixed
	 * @throws ConfigurationException
	 */
	public function getValueOrFail($key) {
		$value = $this->getValueOrNull($key);

		if (is_null($value)) {
			throw new ConfigurationException('Configuration key not found: %s.', $key);
		}

		return $value;
	}

	/**
	 * Returns given configuration unserialized option value or default value if option was not found.
	 * @param string $key
	 * @return mixed
	 */
	public function getValueOrDefault($key) {
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
	public function getDefaultValue($key) {
		// @todo

		if ($key === 'language') {
			return 'pl';
		}

		return 'asdf';
	}

}
