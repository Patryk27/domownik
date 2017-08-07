<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class HelpController
	extends BaseController {

	/**
	 * @return mixed
	 */
	public function error404() {
		return view('views.dashboard.help.errors.404');
	}

}