<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;

class HelpController
	extends BaseController {

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionError404() {
		return view('dashboard.help/error-404');
	}

}