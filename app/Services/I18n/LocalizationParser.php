<?php

namespace App\Services\I18n;

use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Filesystem\Filesystem;

class LocalizationParser {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @var string[]
	 */
	protected $messages;

	/**
	 * @param LoggerContract $log
	 * @param Filesystem $fs
	 */
	public function __construct(
		LoggerContract $log,
		Filesystem $fs
	) {
		$this->log = $log;
		$this->fs = $fs;
	}

	/**
	 * @param string $directoryPath
	 * @return string[]
	 */
	public function parseDirectory(string $directoryPath): array {
		$this->messages = [];

		if (!$this->fs->exists($directoryPath)) {
			$this->log->warning('Directory could not have been found: %s.', $directoryPath);
			return [];
		}

		$directories = $this->fs->directories($directoryPath);

		foreach ($directories as $directory) {
			$this->scanLanguageDirectory($directory);
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
		$result = [];

		$paths = $this->fs->glob($path . '/*');

		foreach ($paths as $path) {
			$pathInfo = pathinfo($path);
			$fileName = $pathInfo['filename'];

			if ($this->fs->isFile($path)) {
				$result[$fileName] = require $path;
			} else {
				$result[$fileName] = $this->scanDirectory($path);
			}
		}

		return $result;
	}

}