<?php

namespace App\Modules\Scaffolding\Module;

use App\Modules\ScaffoldingContract\Module\ServiceProvider as ServiceProviderContract;
use App\Services\Module\Manager;

abstract class ServiceProvider
	extends \Illuminate\Support\ServiceProvider
	implements ServiceProviderContract {

	/**
	 * @var string
	 */
	protected $moduleName;

	/**
	 * ServiceProvider constructor.
	 * @param \Illuminate\Contracts\Foundation\Application $app
	 */
	public function __construct(\Illuminate\Contracts\Foundation\Application $app) {
		parent::__construct($app);
	}

	/**
	 * @inheritdoc
	 */
	public function boot(): ServiceProviderContract {
		$viewsDir = $this->getModuleDirectory('Views') . DIRECTORY_SEPARATOR;
		$resourcesDir = $this->getModuleDirectory('Resources') . DIRECTORY_SEPARATOR;

		$this->loadViewsFrom($viewsDir, $this->moduleName);
		$this->loadTranslationsFrom($resourcesDir . 'lang', $this->moduleName);

		require_once $resourcesDir . 'routes.php';

		return $this;
	}

	/**
	 * @return string
	 */
	public function getModuleName(): string {
		return $this->moduleName;
	}

	/**
	 * @param string $moduleName
	 * @return $this
	 */
	public function setModuleName(string $moduleName): ServiceProviderContract {
		$this->moduleName = $moduleName;
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