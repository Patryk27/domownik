<?php

namespace App\Support\Classes;

class Module {

	/**
	 * @var \App\Modules\Scaffolding\Module\DirectorContract
	 */
	protected $activeModule;

	/**
	 * @param string $key
	 * @return string|string[]
	 */
	public function getTranslation($key) {
		return \App\Support\Facades\Translation::getModuleTranslation($this->activeModule->getName(), $key);
	}

	/**
	 * @return string
	 */
	public function getActiveModule() {
		return $this->activeModule;
	}

	/**
	 * @param string $activeModule
	 * @return $this
	 */
	public function setActiveModule($activeModule) {
		$this->activeModule = $activeModule;
		return $this;
	}

}