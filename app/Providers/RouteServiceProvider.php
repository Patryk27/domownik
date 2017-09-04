<?php

namespace App\Providers;

use App\Services\Install\ManagerContract as InstallManagerContract;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider
    extends ServiceProvider {

    /**
     * @param InstallManagerContract $installManager
     * @return void
     */
    public function map(
        InstallManagerContract $installManager
    ): void {
        if ($installManager->isApplicationInstalled()) {
            $this->createApplicationRoutes();
        } else {
            $this->createInstallerRoutes();
        }
    }

    /**
     * @return void
     */
    protected function createApplicationRoutes(): void {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        Route::middleware('web')
            ->group(base_path('routes/web/dashboard.php'));

        Route::middleware('web')
            ->group(base_path('routes/web/finances.php'));
    }

    /**
     * @return void
     */
    protected function createInstallerRoutes(): void {
        Route::middleware('web')
            ->group(base_path('routes/web/installer.php'));
    }

}
