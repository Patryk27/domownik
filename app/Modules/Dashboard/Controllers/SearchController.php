<?php

namespace App\Modules\Dashboard\Controllers;

class SearchController
	extends \App\Http\Controllers\Controller {

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionIndex() {
		return view('Dashboard::index/index');
	}

}