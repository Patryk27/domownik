<?php

namespace App\Modules\Installer\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class InstallController
	extends BaseController {

	use DispatchesJobs, ValidatesRequests;

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionIndex() {
		return view('Installer::install.index');
	}

}
