<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use View;

abstract class Controller
	extends BaseController {

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * @param string $messageType
	 * @param string $message
	 * @return $this
	 */
	protected function putFlash(string $messageType, string $message) {
		flash($message, $messageType);
		return $this;
	}

	/**
	 * @param string $messageType
	 * @param string $message
	 * @return $this
	 */
	protected function putMessage(string $messageType, string $message) {
		$layoutMessages = View::shared('layoutMessages', []);
		$layoutMessages[] = [
			'type' => $messageType,
			'message' => $message,
		];

		View::share('layoutMessages', $layoutMessages);

		return $this;
	}

}
