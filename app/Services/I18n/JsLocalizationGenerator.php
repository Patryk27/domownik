<?php

namespace App\Services\I18n;

use Illuminate\Filesystem\Filesystem;

class JsLocalizationGenerator {

	/**
	 * @var Filesystem
	 */
	protected $fs;

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
	 * @param LocalizationParser $localizationParser
	 */
	public function __construct(
		Filesystem $fs,
		LocalizationParser $localizationParser
	) {
		$this->fs = $fs;
		$this->localizationParser = $localizationParser;
	}

	/**
	 * @return $this
	 */
	public function generateLocalizationFile() {
		$messages = $this->getMessages();

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
	 * @return $this
	 */
	public function setLocalizationFileName(string $localizationFileName) {
		$this->localizationFileName = $localizationFileName;
		return $this;
	}

	/**
	 * @return string[]
	 */
	protected function getMessages(): array {
		$result = [];

		$messages = $this->localizationParser->parseDirectory(resource_path('lang'));

		foreach ($messages as $languageCode => $baseMessages) {
			if (isset($baseMessages['js'])) {
				$result[$languageCode] = $baseMessages['js'];
			}
		}

		return $result;
	}

}