<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel
	extends ConsoleKernel {

	/**
	 * @var array
	 */
	protected $commands = [
		Commands\Finances\ProcessTransactionSchedule::class,
		Commands\Localization\Update::class,
	];

	/**
	 * @return void
	 */
	protected function commands() {
		require base_path('routes/console.php');
	}

}