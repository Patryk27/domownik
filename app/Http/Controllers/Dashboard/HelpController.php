<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;
use App\Services\Breadcrumb\ManagerContract as BreadcrumbManagerContract;

class HelpController
	extends BaseController {

	/**
	 * @var BreadcrumbManagerContract
	 */
	protected $breadcrumbManager;

	/**
	 * @param int $errorCode
	 * @return mixed
	 */
	public function httpError(int $errorCode) {
		if (!in_array($errorCode, [404])) {
			abort(404, sprintf('Invalid HTTP error code: [%d].', $errorCode));
		}

		$this->breadcrumbManager
			// @todo ->pushUrl(route('dashboard.help.index'), ...)
			->pushUrl(route('dashboard.help.http-error', $errorCode), __('breadcrumbs.help.http-error', [
				'errorCode' => $errorCode,
			]));

		return view('views.dashboard.help.http-error', [
			'errorCode' => $errorCode,
		]);
	}

}