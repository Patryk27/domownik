<?php

namespace App\Modules\Dashboard\Module;

use App\Models\User;
use App\Modules\Scaffolding\Module\ServiceProvider as AbstractServiceProvider;
use App\Modules\ScaffoldingContract\Module\ServiceProvider as ServiceProviderContract;
use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\ValueObjects\Breadcrumb;

class ServiceProvider
	extends AbstractServiceProvider {

	/**
	 * @inheritdoc
	 */
	public function boot(string $moduleName): ServiceProviderContract {
		parent::boot($moduleName);

		$this->bindServices()
			 ->prepareBreadcrumbs();

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Modules\Dashboard\Services\User\RequestManagerContract::class, \App\Modules\Dashboard\Services\User\RequestManager::class);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function prepareBreadcrumbs(): self {
		$breadcrumbManager = $this->app->make(\App\Services\Breadcrumb\Manager::class);

		/**
		 * Breadcrumb of a budget.
		 */
		$breadcrumbManager->registerCustomPushHandler(new class
			implements CustomPushHandlerContract {

			/**
			 * @inheritDoc
			 */
			public function getBreadcrumb($custom) {
				if (is_object($custom) && $custom instanceof User) {
					return new Breadcrumb(
						route('dashboard.user.edit', $custom->id),
						__('Dashboard::breadcrumb.user.edit', [
							'userName' => $custom->full_name,
						])
					);
				}

				return null;
			}

		});

		return $this;
	}

}