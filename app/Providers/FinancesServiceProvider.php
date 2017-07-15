<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\TransactionPeriodicityDaily;
use App\Models\TransactionPeriodicityMonthly;
use App\Models\TransactionPeriodicityOneShot;
use App\Models\TransactionPeriodicityWeekly;
use App\Models\TransactionPeriodicityYearly;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\ValueObjects\Breadcrumb;
use Illuminate\Database\Eloquent\Relations\Relation;

class FinancesServiceProvider
	extends ServiceProvider {

	/**
	 * @inheritdoc
	 */
	public function register() {
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
		$this->app->bind(\App\Repositories\Contracts\BudgetConsolidationRepositoryContract::class, \App\Repositories\Eloquent\BudgetConsolidationRepository::class);
		$this->app->bind(\App\Repositories\Contracts\BudgetRepositoryContract::class, \App\Repositories\Eloquent\BudgetRepository::class);
		$this->app->bind(\App\Repositories\Contracts\TransactionCategoryRepositoryContract::class, \App\Repositories\Eloquent\TransactionCategoryRepository::class);
		$this->app->bind(\App\Repositories\Contracts\TransactionPeriodicityRepositoryContract::class, \App\Repositories\Eloquent\TransactionPeriodicityRepository::class);
		$this->app->bind(\App\Repositories\Contracts\TransactionRepositoryContract::class, \App\Repositories\Eloquent\TransactionRepository::class);
		$this->app->bind(\App\Repositories\Contracts\TransactionScheduleRepositoryContract::class, \App\Repositories\Eloquent\TransactionScheduleRepository::class);

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function bindServices(): self {
		$this->app->bind(\App\Services\Budget\RequestManagerContract::class, \App\Services\Budget\RequestManager::class);
		$this->app->bind(\App\Services\Transaction\HistoryCollectorContract::class, \App\Services\Transaction\HistoryCollector::class);
		$this->app->bind(\App\Services\Transaction\PeriodicityParserContract::class, \App\Services\Transaction\PeriodicityParser::class);
		$this->app->bind(\App\Services\Transaction\RequestManagerContract::class, \App\Services\Transaction\RequestManager::class);
		$this->app->bind(\App\Services\Transaction\Category\RequestManagerContract::class, \App\Services\Transaction\Category\RequestManager::class);
		$this->app->bind(\App\Services\Transaction\Schedule\ProcessorContract::class, \App\Services\Transaction\Schedule\Processor::class);
		$this->app->bind(\App\Services\Transaction\Schedule\UpdaterContract::class, \App\Services\Transaction\Schedule\Updater::class);

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
						__('breadcrumbs.budget.show', [
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
