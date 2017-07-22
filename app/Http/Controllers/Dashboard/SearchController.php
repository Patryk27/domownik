<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class SearchController
	extends BaseController {

	/**
	 * @return mixed
	 */
	public function actionIndex() {
		return view('dashboard.index/index');
	}

}