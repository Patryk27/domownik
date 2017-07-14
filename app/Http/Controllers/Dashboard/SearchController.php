<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class SearchController
	extends BaseController {

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionIndex() {
		return view('dashboard.index/index');
	}

}