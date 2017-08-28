<?php

namespace App\Support\Classes;

use App\Services\Breadcrumb\ManagerContract as BreadcrumbManagerContract;
use Illuminate\Support\Collection;

class Breadcrumb {

	/**
	 * @var BreadcrumbManagerContract
	 */
	protected $breadcrumbManager;

	/**
	 * @param BreadcrumbManagerContract $breadcrumbManager
	 */
	public function __construct(
		BreadcrumbManagerContract $breadcrumbManager
	) {
		$this->breadcrumbManager = $breadcrumbManager;
	}

	/**
	 * @return Collection|Breadcrumb[]
	 */
	public function getBreadcrumbs(): Collection {
		return $this->breadcrumbManager->getBreadcrumbs();
	}

}