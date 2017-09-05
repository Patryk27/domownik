<?php

namespace App\Services\Translation;

use App\Services\Translation\Manager\Scanner as TranslationScanner;
use Illuminate\Filesystem\Filesystem;

class Manager
	implements ManagerContract {

	/**
	 * @var Filesystem
	 */
	protected $fs;

	/**
	 * @var TranslationScanner
	 */
	protected $translationScanner;

	/**
	 * @param Filesystem $fs
	 * @param TranslationScanner $translationScanner
	 */
	public function __construct(
		Filesystem $fs,
		TranslationScanner $translationScanner
	) {
		$this->fs = $fs;
		$this->translationScanner = $translationScanner;
	}

	/**
	 * @inheritDoc
	 */
	public function getLanguages(): array {
		$languagesDirectory = resource_path('lang');
		$languageDirectories = $this->fs->directories($languagesDirectory);

		return array_map(function (string $languageDirectory) {
			return pathinfo($languageDirectory)['basename'];
		}, $languageDirectories);
	}

	/**
	 * @inheritDoc
	 */
	public function getTranslationByLanguage(string $language): array {
		return $this->translationScanner->getTranslationByLanguage($language);
	}

}