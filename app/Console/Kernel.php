<?php

namespace App\Console;

use App\Services\Install\ManagerContract as InstallManagerContract;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel
	extends ConsoleKernel {

	/**
	 * @var array
	 */
	protected $commandsApplication = [
		Commands\App\CompileAssets::class,
		Commands\App\ProcessTransactionSchedule::class,
	];

	/**
	 * @inheritdoc
	 */
	public function commands() {
		$installManager = $this->app->make(InstallManagerContract::class);

		if ($installManager->isApplicationInstalled()) {
			$this->commands = $this->commandsApplication;
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function schedule(Schedule $schedule) {
		$schedule->call(function () {
			$this->artisan->call('app:process-transaction-schedule');
		})->dailyAt('00:05');
	}

}