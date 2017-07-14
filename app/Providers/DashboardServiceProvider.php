<?php

namespace App\Providers;

use App\Models\User;
use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\ValueObjects\Breadcrumb;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider
	extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	public function register() {
		$this->bindServices()
			 ->prepareBreadcrumbs();

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Services\User\RequestManagerContract::class, \App\Services\User\RequestManager::class);

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