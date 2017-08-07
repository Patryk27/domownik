<?php

namespace App\Providers;

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
use Illuminate\Support\ServiceProvider;

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
		$this->app->bind(\App\Services\Budget\Request\ProcessorContract::class, \App\Services\Budget\Request\Processor::class);

		$this->app->bind(\App\Services\Transaction\Category\Request\ProcessorContract::class, \App\Services\Transaction\Category\Request\Processor::class);
		$this->app->bind(\App\Services\Transaction\Category\TransformatorContract::class, \App\Services\Transaction\Category\Transformator::class);
		$this->app->bind(\App\Services\Transaction\Periodicity\ParserContract::class, \App\Services\Transaction\Periodicity\Parser::class);
		$this->app->bind(\App\Services\Transaction\Request\ProcessorContract::class, \App\Services\Transaction\Request\Processor::class);
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
				if (is_object($custom)) {
					if ($custom instanceof Budget) {
						return $this->getBudgetBreadcrumb($custom);
					} elseif ($custom instanceof Transaction) {
						return $this->getTransactionBreadcrumb($custom);
					}
				}
				return null;
			}

			/**
			 * @param Budget $budget
			 * @return Breadcrumb
			 */
			protected function getBudgetBreadcrumb(Budget $budget) {
				return new Breadcrumb(
					route('finances.budgets.show', $budget->id),
					__('breadcrumbs.budgets.show', [
						'budgetName' => $budget->name,
					])
				);
			}

			/**
			 * @param Transaction $transaction
			 * @return Breadcrumb
			 */
			protected function getTransactionBreadcrumb(Transaction $transaction) {
				return new Breadcrumb(
					route('finances.transactions.show', $transaction->id),
					__('breadcrumbs.transactions.show', [
						'transactionName' => $transaction->name,
					])
				);
			}

		});

		return $this;
	}

}
