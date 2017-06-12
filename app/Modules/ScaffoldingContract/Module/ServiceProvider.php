<?php

namespace App\Modules\ScaffoldingContract\Module;

interface ServiceProvider {

	/**
	 * @return $this
	 */
	public function boot(string $moduleName): ServiceProvider;

	/**
	 * @return Sidebar
	 */
	public function getSidebar(): Sidebar;

}