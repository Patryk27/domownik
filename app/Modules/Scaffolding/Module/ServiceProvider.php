<?php

namespace App\Modules\Scaffolding\Module;

use App\Services\Module\Manager;
use Illuminate\Contracts\Foundation\Application;

abstract class ServiceProvider
	extends \Illuminate\Support\ServiceProvider
	implements ServiceProviderContract {

	/**
	 * @var string
	 */
	protected $moduleName;

	/**
	 * @var Sidebar
	 */
	protected $sidebar;

	/**
	 * @param Application $app
	 */
	public function __construct(Application $app) {
		parent::__construct($app);
	}

	/**
	 * @inheritdoc
	 */
	public function boot(string $moduleName): ServiceProviderContract {
		$this->moduleName = $moduleName;

		$this
			->loadViews()
			->loadResources()
			->loadSidebar();

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getSidebar(): SidebarContract {
		return $this->sidebar;
	}

	/**
	 * @return ServiceProvider
	 */
	protected function loadViews(): self {
		$viewsDir = $this->getModuleDirectory('Views') . DIRECTORY_SEPARATOR;
		$this->loadViewsFrom($viewsDir, $this->moduleName);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function loadResources(): self {
		$resourcesDir = $this->getModuleDirectory('Resources') . DIRECTORY_SEPARATOR;
		$this->loadTranslationsFrom($resourcesDir . 'lang', $this->moduleName);

		require_once $resourcesDir . 'routes.php';

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function loadSidebar(): self {
		$resourcesDir = $this->getModuleDirectory('Resources') . DIRECTORY_SEPARATOR;
		$sidebarFileName = $resourcesDir . 'sidebar.xml';

		$this->sidebar = $this->app->make(Sidebar::class);
		$this->sidebar->loadFromFile($sidebarFileName);

		return $this;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function getModuleDirectory(string $path): string {
		return Manager::getModuleDirectory($this->moduleName, $path);
	}

}