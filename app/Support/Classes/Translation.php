<?php

namespace App\Support\Classes;

class Translation {

	/**
	 * Translates given module name.
	 * @param string $moduleName
	 * @return string
	 */
	public function getModuleName($moduleName) {
		return $this->getModuleTranslation($moduleName, ['main', $moduleName]);
	}

	/**
	 * Returns module's language namespace, for example: 'Dashboard::'.
	 * @param string $moduleName
	 * @return string
	 */
	public function getModuleLanguageNamespace($moduleName) {
		return sprintf('%s::', $moduleName);
	}

	/**
	 * Translates given string according to the module's namespace.
	 * @param string $moduleName
	 * @param string|string[] $key
	 * @return string
	 */
	public function getModuleTranslation($moduleName, $key) {
		if (!is_array($key)) {
			$key = [$key];
		}

		return __($this->getModuleLanguageNamespace($moduleName) . strtolower(implode('.', $key)));
	}

}