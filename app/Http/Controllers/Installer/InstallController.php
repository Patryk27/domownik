<?php

namespace App\Http\Controllers\Installer;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class InstallController
	extends BaseController {

	use DispatchesJobs, ValidatesRequests;

	/**
	 * @return mixed
	 */
	public function actionIndex() {
		return view('Installer::install.index');
	}

}
