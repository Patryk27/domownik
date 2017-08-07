<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class IndexController
	extends BaseController {

	/**
	 * @return mixed
	 */
	public function index() {
		return view('views.dashboard.index.index');
	}

}