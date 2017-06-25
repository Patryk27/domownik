<?php

namespace App\Support\Classes;

use App\Services\Configuration\Manager as ConfigurationManager;

class Configuration {

	/**
	 * @var ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @param ConfigurationManager $configurationManager
	 */
	public function __construct(ConfigurationManager $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	/**
	 * @return string
	 */
	public function getLanguage() {
		return $this->configurationManager->getValueOrDefault('language');
	}

}