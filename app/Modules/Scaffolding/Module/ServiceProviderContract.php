<?php

namespace App\Modules\Scaffolding\Module;

interface ServiceProviderContract {

	/**
	 * @param string $moduleName
	 * @return $this
	 */
	public function boot(string $moduleName): ServiceProviderContract;

	/**
	 * @return SidebarContract
	 */
	public function getSidebar(): SidebarContract;

}