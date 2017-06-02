<?php

namespace App\Modules\Dashboard\Module;

class Director
	extends \App\Modules\Scaffolding\Module\Director {

	/**
	 * Director constructor.
	 * @param Sidebar $sidebar
	 */
	public function __construct(Sidebar $sidebar) {
		$this->sidebar = $sidebar;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return 'Dashboard';
	}

}
