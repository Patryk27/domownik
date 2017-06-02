<?php

namespace App\Modules\Scaffolding\Module;

use App\Modules\ScaffoldingContract\Module\Director as DirectorContract;
use App\Modules\ScaffoldingContract\Module\Sidebar as SidebarContract;
use App\Services\Module\Manager as ModuleManager;

abstract class Director
	implements DirectorContract {

	/**
	 * @var Sidebar
	 */
	protected $sidebar;

	/**
	 * @inheritdoc
	 */
	public function boot(): DirectorContract {
		$resourcesDir = $this->getDirectory('Resources') . DIRECTORY_SEPARATOR;
		$this->sidebar->loadFromFile($resourcesDir . 'sidebar.xml');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function initialize(): DirectorContract {
		// nottin' here for now
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function is($moduleDirector): bool {
		return $moduleDirector->getName() === $this->getName();
	}

	/**
	 * @inheritdoc
	 */
	public function getDirectory(string $path = ''): string {
		return ModuleManager::getModuleDirectory($this->getName(), $path);
	}

	/**
	 * @return Sidebar
	 */
	public function getSidebar(): SidebarContract {
		return $this->sidebar;
	}

}