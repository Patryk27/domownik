<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;

class InstallController
	extends Controller {

	/**
	 * @return mixed
	 */
	public function index() {
		return view('views.installer.install.index');
	}

}
