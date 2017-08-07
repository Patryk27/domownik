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
		$this
			->bindServices()
			->prepareBreadcrumbs();

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Services\User\Request\ProcessorContract::class, \App\Services\User\Request\Processor::class);

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
				if (is_object($custom)) {
					if ($custom instanceof User) {
						return $this->getUserBreadcrumb($custom);
					}
				}

				return null;
			}

			/**
			 * @param User $user
			 * @return Breadcrumb
			 */
			protected function getUserBreadcrumb(User $user) {
				return new Breadcrumb(
					route('dashboard.users.edit', $user->id),
					__('breadcrumbs.users.edit', [
						'userName' => $user->full_name,
					])
				);
			}

		});

		return $this;
	}

}