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
use App\Modules\Scaffolding\Module\ServiceProviderContract;
use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\ValueObjects\Breadcrumb;
use Illuminate\Database\Eloquent\Relations\Relation;

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
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\BudgetConsolidationRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\BudgetConsolidationRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\BudgetRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionCategoryRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionPeriodicityRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionScheduleRepository::class);
		$this->app->bind(\App\Modules\Finances\Repositories\Contracts\TransactionValueRepositoryContract::class, \App\Modules\Finances\Repositories\Eloquent\TransactionValueRepository::class);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Modules\Finances\Services\Budget\RequestManagerContract::class, \App\Modules\Finances\Services\Budget\RequestManager::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\HistoryCollectorContract::class, \App\Modules\Finances\Services\Transaction\HistoryCollector::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\PeriodicityParserContract::class, \App\Modules\Finances\Services\Transaction\PeriodicityParser::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\RequestManagerContract::class, \App\Modules\Finances\Services\Transaction\RequestManager::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\Category\RequestManagerContract::class, \App\Modules\Finances\Services\Transaction\Category\RequestManager::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\Schedule\ProcessorContract::class, \App\Modules\Finances\Services\Transaction\Schedule\Processor::class);
		$this->app->bind(\App\Modules\Finances\Services\Transaction\Schedule\UpdaterContract::class, \App\Modules\Finances\Services\Transaction\Schedule\Updater::class);

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