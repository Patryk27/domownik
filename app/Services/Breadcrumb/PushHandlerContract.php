<?php

namespace App\Services\Breadcrumb;

use App\ValueObjects\Breadcrumb;

interface PushHandlerContract {

	/**
	 * @param mixed $value
	 * @return Breadcrumb|null
	 */
	public function handle($value): ?Breadcrumb;

}