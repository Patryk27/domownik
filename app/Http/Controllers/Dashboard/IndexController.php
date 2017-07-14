<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class IndexController
	extends BaseController {

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionIndex() {
		return view('views.dashboard.index.index');
	}

}