<?php

namespace App\Services\Asset\Compiler;

use App\Services\Translation\ManagerContract as TranslationManagerContract;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Output\ConsoleOutput;

class LocalizationCompiler {

	/**
	 * @var ConsoleOutput
	 */
	protected $console;

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @var TranslationManagerContract
	 */
	protected $translationManager;

	/**
	 * @param ConsoleOutput $console
	 * @param Filesystem $fs
	 * @param TranslationManagerContract $translationManager
	 */
	public function __construct(
		ConsoleOutput $console,
		Filesystem $fs,
		TranslationManagerContract $translationManager
	) {
		$this->console = $console;
		$this->fs = $fs;
		$this->translationManager = $translationManager;
	}

	/**
	 * @return $this
	 */
	public function compile() {
		$this->console->writeln('-> creating JavaScript localization file...');

		$localizationFileName = $this->getLocalizationFileName();
		$localizationMessages = $this->getLocalizationMessages();

		$localizationFileContent = sprintf('window.AppLocalizationMessages = %s;', json_encode($localizationMessages));

		$this->fs->put($localizationFileName, $localizationFileContent);

		return $this;
	}

	/**
	 * @return string
	 */
	protected function getLocalizationFileName(): string {
		return public_path('js/localization.js');
	}

	/**
	 * Returns all the available translation messages.
	 * @return array
	 */
	protected function getLocalizationMessages(): array {
		$result = [];

		$languages = $this->translationManager->getLanguages();

		foreach ($languages as $language) {
			$result[$language] = $this->translationManager->getTranslationByLanguage($language);
		}

		return $result;
	}

}