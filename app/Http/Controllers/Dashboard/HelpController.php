<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class HelpController
	extends BaseController {

	/**
	 * @return mixed
	 */
	public function error404() {
		return view('dashboard.help/error-404');
	}

}