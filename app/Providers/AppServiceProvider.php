<?php

namespace App\Providers;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Breadcrumb\PushHandlerContract;
use App\ValueObjects\Breadcrumb;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider
    extends ServiceProvider {

    /**
     * @return void
     */
    public function register(): void {
        View::composer('*', \App\Http\ViewComposers\SectionComposer::class);

        $this
            ->registerRepositories()
            ->registerServices()
            ->prepareDatabase()
            ->prepareBreadcrumbs();

        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * @return void
     */
    public function boot(): void {
        // @todo should not be hard-coded
        date_default_timezone_set('Europe/Warsaw');
        Carbon::setLocale('pl');
        setlocale(LC_TIME, 'pl', 'pl-PL');
    }

    /**
     * @return $this
     */
    protected function registerRepositories() {
        $this->app->bind(\App\Repositories\Contracts\BudgetConsolidationRepositoryContract::class, \App\Repositories\Eloquent\BudgetConsolidationRepository::class);
        $this->app->bind(\App\Repositories\Contracts\BudgetRepositoryContract::class, \App\Repositories\Eloquent\BudgetRepository::class);
        $this->app->bind(\App\Repositories\Contracts\SettingRepositoryContract::class, \App\Repositories\Eloquent\SettingRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TransactionCategoryRepositoryContract::class, \App\Repositories\Eloquent\TransactionCategoryRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TransactionPeriodicityRepositoryContract::class, \App\Repositories\Eloquent\TransactionPeriodicityRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TransactionRepositoryContract::class, \App\Repositories\Eloquent\TransactionRepository::class);
        $this->app->bind(\App\Repositories\Contracts\TransactionScheduleRepositoryContract::class, \App\Repositories\Eloquent\TransactionScheduleRepository::class);
        $this->app->bind(\App\Repositories\Contracts\UserRepositoryContract::class, \App\Repositories\Eloquent\UserRepository::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerServices() {
        $this->app->singleton(\App\Services\Breadcrumb\ManagerContract::class, \App\Services\Breadcrumb\Manager::class);

        $this->app->bind(\App\Services\Budget\Request\ProcessorContract::class, \App\Services\Budget\Request\Processor::class);
        $this->app->bind(\App\Services\Budget\SummaryGeneratorContract::class, \App\Services\Budget\SummaryGenerator::class);

        $this->app->bind(\App\Services\Install\ManagerContract::class, \App\Services\Install\Manager::class);

        $this->app->bind(\App\Services\Search\Transaction\OneShotSearchContract::class, \App\Services\Search\Transaction\OneShotSearch::class);
        $this->app->bind(\App\Services\Search\Transaction\ScheduleSearchContract::class, \App\Services\Search\Transaction\ScheduleSearch::class);

        $this->app->bind(\App\Services\Section\ManagerContract::class, \App\Services\Section\Manager::class);

        $this->app->bind(\App\Services\Sidebar\ManagerContract::class, \App\Services\Sidebar\Manager::class);
        $this->app->bind(\App\Services\Sidebar\ParserContract::class, \App\Services\Sidebar\Parser::class);

        $this->app->bind(\App\Services\Transaction\Category\Request\ProcessorContract::class, \App\Services\Transaction\Category\Request\Processor::class);
        $this->app->bind(\App\Services\Transaction\Category\TransformatorContract::class, \App\Services\Transaction\Category\Transformator::class);
        $this->app->bind(\App\Services\Transaction\Periodicity\ParserContract::class, \App\Services\Transaction\Periodicity\Parser::class);
        $this->app->bind(\App\Services\Transaction\Request\ProcessorContract::class, \App\Services\Transaction\Request\Processor::class);
        $this->app->bind(\App\Services\Transaction\Schedule\ProcessorContract::class, \App\Services\Transaction\Schedule\Processor::class);
        $this->app->bind(\App\Services\Transaction\Schedule\UpdaterContract::class, \App\Services\Transaction\Schedule\Updater::class);

        $this->app->bind(\App\Services\User\Request\ProcessorContract::class, \App\Services\User\Request\Processor::class);

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepareDatabase() {
        Relation::morphMap([
            'budget' => Budget::class,
            'transaction' => Transaction::class,
            'transaction-periodicity-one-shot' => \App\Models\TransactionPeriodicityOneShot::class,
            'transaction-periodicity-daily' => \App\Models\TransactionPeriodicityDaily::class,
            'transaction-periodicity-weekly' => \App\Models\TransactionPeriodicityWeekly::class,
            'transaction-periodicity-monthly' => \App\Models\TransactionPeriodicityMonthly::class,
            'transaction-periodicity-yearly' => \App\Models\TransactionPeriodicityYearly::class,
            'transaction-value-constant' => \App\Models\TransactionValueConstant::class,
            'transaction-value-range' => \App\Models\TransactionValueRange::class,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function prepareBreadcrumbs() {
        $breadcrumbManager = $this->app->make(\App\Services\Breadcrumb\ManagerContract::class);
        $breadcrumbManager->registerPushHandler(new class
            implements PushHandlerContract {

            /**
             * @inheritDoc
             */
            public function handle($value): ?Breadcrumb {
                if (is_object($value)) {
                    if ($value instanceof User) {
                        return $this->getUserBreadcrumb($value);
                    } elseif ($value instanceof Budget) {
                        return $this->getBudgetBreadcrumb($value);
                    } elseif ($value instanceof Transaction) {
                        return $this->getTransactionBreadcrumb($value);
                    }
                }

                return null;
            }

            /**
             * @param User $user
             * @return Breadcrumb
             */
            protected function getUserBreadcrumb(User $user): Breadcrumb {
                return new Breadcrumb(
                    route('dashboard.users.edit', $user->id),
                    __('breadcrumbs.users.edit', [
                        'userName' => $user->full_name,
                    ])
                );
            }

            /**
             * @param Budget $budget
             * @return Breadcrumb
             */
            protected function getBudgetBreadcrumb(Budget $budget): Breadcrumb {
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
            protected function getTransactionBreadcrumb(Transaction $transaction): Breadcrumb {
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
