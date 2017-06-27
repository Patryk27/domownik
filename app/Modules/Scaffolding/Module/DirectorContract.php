<?php

namespace App\Modules\Scaffolding\Module;

interface DirectorContract {

	/**
	 * Boots the module - all views, translations etc. are available here.
	 * This method is called for each enabled module.
	 * @param ServiceProviderContract $serviceProvider
	 * @return $this
	 */
	public function boot(ServiceProviderContract $serviceProvider): DirectorContract;

	/**
	 * Initializes the module.
	 * This method is called solely for currently active module.
	 * @return $this
	 */
	public function initialize(): DirectorContract;

	/**
	 * Returns true if given director is current director.
	 * Can be used for example to compare if looped director is the active director.
	 * @param DirectorContract $moduleDirector
	 * @return bool
	 */
	public function is($moduleDirector): bool;

	/**
	 * Returns module's name.
	 * Should be the same as the module's directory name.
	 * @return string
	 */
	public function getName(): string;

	/**
	 * Returns absolute module's directory path.
	 * @param string $path
	 * @return string
	 */
	public function getDirectory(string $path = ''): string;

	/**
	 * @return SidebarContract
	 */
	public function getSidebar(): SidebarContract;

}