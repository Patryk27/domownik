<?php

namespace App\Services\I18n;

use App\Services\Module\Manager as ModuleManager;
use Illuminate\Filesystem\Filesystem;

class JsLocalizationGenerator {

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @var ModuleManager
	 */
	protected $moduleManager;

	/**
	 * @var LocalizationParser
	 */
	protected $localizationParser;

	/**
	 * @var string
	 */
	protected $localizationFileName;

	/**
	 * @param Filesystem $fs
	 * @param ModuleManager $moduleManager
	 * @param LocalizationParser $localizationParser
	 */
	public function __construct(
		Filesystem $fs,
		ModuleManager $moduleManager,
		LocalizationParser $localizationParser
	) {
		$this->fs = $fs;
		$this->moduleManager = $moduleManager;
		$this->localizationParser = $localizationParser;
	}

	/**
	 * @return $this
	 */
	public function generateLocalizationFile() {
		$messages = array_merge($this->getModuleMessages(), $this->getBaseMessages());

		$fileContent = sprintf('window.AppLocalizationMessages = %s;', json_encode($messages));
		$this->fs->put($this->localizationFileName, $fileContent);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLocalizationFileName(): string {
		return $this->localizationFileName;
	}

	/**
	 * @param string $localizationFileName
	 * @return JsLocalizationGenerator
	 */
	public function setLocalizationFileName(string $localizationFileName): JsLocalizationGenerator {
		$this->localizationFileName = $localizationFileName;
		return $this;
	}

	/**
	 * @return string[]
	 */
	protected function getModuleMessages() {
		$result = [];

		$this->moduleManager->scanModules();

		$moduleNames = $this->moduleManager->getFoundModuleNames();

		foreach ($moduleNames as $moduleName) {
			$rawModuleMessages = $this->localizationParser->parseModule($moduleName);
			$result[$moduleName] = [];

			foreach ($rawModuleMessages as $languageCode => $moduleMessages) {
				if (isset($moduleMessages['js'])) {
					$result[$moduleName][$languageCode] = $moduleMessages['js'];
				}
			}
		}

		return $result;
	}

	/**
	 * @return string[]
	 */
	protected function getBaseMessages() {
		$result = [];

		$rawBaseMessages = $this->localizationParser->parseDirectory(resource_path('lang'));

		foreach ($rawBaseMessages as $languageCode => $baseMessages) {
			if (isset($baseMessages['js'])) {
				$result[''][$languageCode] = $baseMessages['js'];
			}
		}

		return $result;
	}

}