<?php

namespace App\Services\I18n;

use App\Services\Module\Manager as ModuleManager;
use Illuminate\Filesystem\Filesystem;

class LocalizationParser {

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var string[]
	 */
	protected $messages;

	/**
	 * ModuleMessageParser constructor.
	 * @param Filesystem $filesystem
	 */
	public function __construct(
		Filesystem $filesystem
	) {
		$this->filesystem = $filesystem;
	}

	/**
	 * @param string $moduleName
	 * @return string[]
	 */
	public function parseModule(string $moduleName): array {
		return $this->parseDirectory(ModuleManager::getModuleDirectory($moduleName, 'Resources\\lang'));
	}

	/**
	 * @param string $directoryPath
	 * @return string[]
	 */
	public function parseDirectory(string $directoryPath): array {
		$this->messages = [];

		if ($this->filesystem->isDirectory($directoryPath)) {
			$directories = $this->filesystem->directories($directoryPath);

			foreach ($directories as $directory) {
				$this->scanLanguageDirectory($directory);
			}
		}

		return $this->messages;
	}

	/**
	 * @return string[]
	 */
	public function getMessages(): array {
		return $this->messages;
	}

	/**
	 * @param string $languagePath
	 * @return $this
	 */
	protected function scanLanguageDirectory($languagePath): LocalizationParser {
		$languagePathInfo = pathinfo($languagePath);
		$languageCode = $languagePathInfo['filename'];

		$this->messages[$languageCode] = $this->scanDirectory($languagePath);

		return $this;
	}

	/**
	 * @param string $path
	 * @return string[]
	 */
	protected function scanDirectory(string $path): array {
		$files = $this->filesystem->glob($path . '\\*', 0);

		$result = [];

		foreach ($files as $file) {
			$fileInfo = pathinfo($file);
			$fileName = $fileInfo['filename'];

			if ($this->filesystem->isDirectory($file)) {
				$result[$fileName] = $this->scanDirectory($file);
			} else {
				$result[$fileName] = require $file;
			}
		}

		return $result;
	}

}