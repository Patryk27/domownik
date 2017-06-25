<?php

namespace App\Modules\Scaffolding\Module;

interface ServiceProviderContract {

	/**
	 * @return $this
	 */
	public function boot(string $moduleName): ServiceProviderContract;

	/**
	 * @return SidebarContract
	 */
	public function getSidebar(): SidebarContract;

}