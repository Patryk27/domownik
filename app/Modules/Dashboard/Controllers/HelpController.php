<?php

namespace App\Modules\Dashboard\Controllers;

class HelpController
	extends \App\Http\Controllers\Controller {

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionError404() {
		return view('Dashboard::help/error-404');
	}

}