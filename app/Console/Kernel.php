<?php

namespace App\Console;

use App\Services\Install\Manager as InstallManager;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel
	extends ConsoleKernel {

	/**
	 * @var array
	 */
	protected $commandsApplication = [
		Commands\ProcessTransactionSchedule::class,
		Commands\RefreshJsTranslations::class,
	];

	/**
	 * @inheritdoc
	 */
	public function commands() {
		$installManager = $this->app->make(InstallManager::class);

		if ($installManager->isApplicationInstalled()) {
			$this->commands = $this->commandsApplication;
		}
	}

}