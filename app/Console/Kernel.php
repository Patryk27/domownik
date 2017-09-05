<?php

namespace App\Console;

use App\Services\Install\ManagerContract as InstallManagerContract;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel
    extends ConsoleKernel {

    /**
     * @var array
     */
    protected $commandsApplication = [
        Commands\App\ProcessTransactionSchedule::class,
        Commands\Assets\Compile::class,
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

}