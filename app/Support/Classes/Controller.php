<?php

namespace App\Support\Classes;

use App\Exceptions\Exception;
use Illuminate\Routing\Route;

class Controller {

	/**
	 * @var Route
	 */
	protected $route;

	/**
	 * @param Route|null $route
	 */
	public function __construct(
		Route $route = null // is 'null' when running from Artisan
	) {
		$this->route = $route;
	}

	/**
	 * Each named route (and thus: view) has its own, unique CSS class, created depending on view path.
	 * For example view 'dashboard/user/login' has CSS class 'view-dashboard-user-login'.
	 * @return string
	 */
	public function getViewCssClass(): string {
		return sprintf('view-%s-%s-%s', $this->getSectionName(), $this->getControllerName(), $this->getActionName());
	}

	/**
	 * @return string
	 */
	public function getSectionName(): string {
		$controllerPath = $this->getControllerPath();
		return strtolower($controllerPath[3]);
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getControllerName(): string {
		$controllerPath = $this->getControllerPath();
		$controllerName = end($controllerPath);

		if (!ends_with($controllerName, 'Controller')) {
			throw new Exception('Route has unknown controller name: %s.', $controllerName);
		}

		$controllerName = strtolower(substr($controllerName, 0, -10));
		return $controllerName;
	}

	/**
	 * @return string
	 */
	public function getActionName(): string {
		return camel_case($this->route->getActionMethod());
	}

	/**
	 * @return string[]
	 */
	protected function getControllerPath(): array {
		if (isset($this->route)) {
			return explode('\\', get_class($this->route->getController()));
		} else {
			return ['', '', '', '', ''];
		}
	}

}