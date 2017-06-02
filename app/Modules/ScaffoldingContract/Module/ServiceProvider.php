<?php

namespace App\Modules\ScaffoldingContract\Module;

interface ServiceProvider {

	/**
	 * @return $this
	 */
	public function boot(): ServiceProvider;

	/**
	 * @param string $moduleName
	 * @return $this
	 */
	public function setModuleName(string $moduleName): ServiceProvider;

	/**
	 * @return string
	 */
	public function getModuleName(): string;

}