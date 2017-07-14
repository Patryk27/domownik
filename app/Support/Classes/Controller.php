<?php

namespace App\Support\Classes;

use Illuminate\Routing\Route;

class Controller {

	/**
	 * @var Route
	 */
	protected $currentRoute;

	/**
	 * The route parameter is nullable because it is null when the application is called from Artisan.
	 * Not-nulling it does not make errors but raises warnings and in order to avoid them, this parameter is nulled.
	 * @param Route|null $currentRoute
	 */
	public function __construct(Route $currentRoute = null) {
		$this->currentRoute = $currentRoute;
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
		$controllerNameParts = $this->getControllerNameParts();

		return strtolower($controllerNameParts[3]);
	}

	/**
	 * @return string
	 */
	public function getControllerName(): string {
		$controllerNameParts = $this->getControllerNameParts();
		$controllerName = end($controllerNameParts);

		if (!ends_with($controllerName, 'Controller')) {
			throw new \App\Exceptions\Exception('Route has unknown controller name: %s.', $controllerName);
		}

		$controllerName = strtolower(substr($controllerName, 0, -10));
		return $controllerName;
	}

	/**
	 * @return string
	 */
	public function getActionName(): string {
		$actionMethod = $this->currentRoute->getActionMethod();

		if (!starts_with($actionMethod, 'action')) {
			throw new \App\Exceptions\Exception('Route has unknown method name: %s.', $actionMethod);
		}

		return camel_case(substr($actionMethod, 6));
	}

	/**
	 * @return string[]
	 */
	protected function getControllerNameParts(): array {
		return explode('\\', get_class($this->currentRoute->getController()));
	}

}