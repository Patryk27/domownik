<?php

namespace App\Services\Translation\Manager;

use App\Exceptions\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class Scanner {

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @param Filesystem $fs
	 */
	public function __construct(
		Filesystem $fs
	) {
		$this->fs = $fs;
	}

	/**
	 * @param string $language
	 * @return array
	 * @throws FileNotFoundException
	 */
	public function getTranslationByLanguage(string $language): array {
		$languagePath = $this->getLanguagePath($language);

		if (!$this->fs->isDirectory($languagePath)) {
			throw new FileNotFoundException('Could not find language path [%s] for language [%s].', $languagePath, $language);
		}

		return $this->processPath($languagePath);
	}

	/**
	 * @param string $language
	 * @return string
	 */
	protected function getLanguagePath(string $language): string {
		return resource_path('lang' . DIRECTORY_SEPARATOR . $language);
	}

	/**
	 * @param string $path
	 * @return array
	 */
	protected function processPath(string $path): array {
		$result = [];

		$files = $this->fs->glob($path . '/*');

		foreach ($files as $file) {
			$fileName = pathinfo($file)['filename'];

			if ($this->fs->isFile($file)) {
				$result[$fileName] = require $file;
			} else {
				$result[$fileName] = $this->processPath($file);
			}
		}

		return $result;
	}

}