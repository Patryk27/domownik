<?php

namespace App\Modules\ScaffoldingContract\Module;

interface Director {

	/**
	 * Boots the module - all views, translations etc. are available here.
	 * This method is called for each enabled module.
	 * @return $this
	 */
	public function boot(): Director;

	/**
	 * Initializes the module.
	 * This method is called only for currently active module.
	 * @return $this
	 */
	public function initialize(): Director;

	/**
	 * Returns true if given director is current director.
	 * Can be used for example to compare if looped director is the active director.
	 * @param Director $moduleDirector
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
	 * @return Sidebar
	 */
	public function getSidebar(): Sidebar;

}