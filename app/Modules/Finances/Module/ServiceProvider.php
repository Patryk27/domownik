<?php

namespace App\Modules\Finances\Module;

use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionPeriodicityDaily;
use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionPeriodicityWeekly;
use App\Modules\Finances\Models\TransactionPeriodicityYearly;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Scaffolding\Module\ServiceProvider as AbstractServiceProvider;
use App\Modules\ScaffoldingContract\Module\ServiceProvider as ServiceProviderContract;
use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\ValueObjects\Breadcrumb;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Application;

class ServiceProvider
	extends AbstractServiceProvider {

	/**
	 * @inheritdoc
	 */
	public function boot(string $moduleName): ServiceProviderContract {
		parent::boot($moduleName);

		$this
			->prepareDatabase()
			->bindRepositories()
			->bindServices()
			->prepareBreadcrumbs();

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function prepareDatabase(): self {
		Relation::morphMap([
			'budget' => Budget::class,
			'transaction' => Transaction::class,
			'transaction-periodicity-one-shot' => TransactionPeriodicityOneShot::class,
			'transaction-periodicity-daily' => TransactionPeriodicityDaily::class,
			'transaction-periodicity-weekly' => TransactionPeriodicityWeekly::class,
			'transaction-periodicity-monthly' => TransactionPeriodicityMonthly::class,
			'transaction-periodicity-yearly' => TransactionPeriodicityYearly::class,
			'transaction-value-constant' => TransactionValueConstant::class,
			'transaction-value-range' => TransactionValueRange::class,
		]);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindRepositories(): self {
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\BudgetRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionCategoryRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionPeriodicityRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionScheduleRepository::class);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract::class, \App\Modules\Finances\Services\Transaction\HistoryCollectorService::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\PeriodicityParserServiceContract::class, \App\Modules\Finances\Services\Transaction\PeriodicityParserService::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\RequestManagerServiceContract::class, \App\Modules\Finances\Services\Transaction\RequestManagerService::class);
		$this->app->bind(\App\Modules\Finances\Services\TransactionCategory\RequestManagerServiceContract::class, \App\Modules\Finances\Services\TransactionCategory\RequestManagerService::class);
		$this->app->bind(\App\Modules\Finances\Services\TransactionSchedule\ProcessorServiceContract::class, \App\Modules\Finances\Services\TransactionSchedule\ProcessorService::class);
		$this->app->bind(\App\Modules\Finances\Services\TransactionSchedule\UpdaterServiceContract::class, \App\Modules\Finances\Services\TransactionSchedule\UpdaterService::class);

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
				if (is_object($custom) && $custom instanceof Budget) {
					return new Breadcrumb(
						route('finances.budget.show', $custom->id),
						__('Finances::breadcrumb.budget.show', [
							'budgetName' => $custom->name,
						])
					);
				}

				return null;
			}

		});

		return $this;
	}

}