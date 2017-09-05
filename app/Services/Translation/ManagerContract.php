<?php

namespace App\Services\Translation;

interface ManagerContract {

	/**
	 * @return array
	 */
	public function getLanguages(): array;

	/**
	 * @param string $language
	 * @return array
	 */
	public function getTranslationByLanguage(string $language): array;

}