<?php

namespace App\Services\Breadcrumb;

use App\ValueObjects\Breadcrumb;
use Illuminate\Support\Collection;

interface ManagerContract {

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function push($value);

	/**
	 * @param string $url
	 * @param string $caption
	 * @return $this
	 */
	public function pushUrl(string $url, string $caption);

	/**
	 * @param PushHandlerContract $pushHandlerContract
	 * @return $this
	 */
	public function registerPushHandler(PushHandlerContract $pushHandlerContract);

	/**
	 * @return Collection|Breadcrumb[]
	 */
	public function getBreadcrumbs(): Collection;

}