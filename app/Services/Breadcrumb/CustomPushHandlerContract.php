<?php

namespace App\Services\Breadcrumb;

use App\ValueObjects\Breadcrumb;

interface CustomPushHandlerContract {

	/**
	 * @param mixed $custom
	 * @return Breadcrumb|null
	 */
	public function getBreadcrumb($custom);

}