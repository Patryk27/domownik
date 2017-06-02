<?php

namespace App\Support\Classes;

use App\Services\Breadcrumb\Manager as BreadcrumbManager;

class Breadcrumb {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * Breadcrumb constructor.
	 * @param BreadcrumbManager $breadcrumbManager
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager
	) {
		$this->breadcrumbManager = $breadcrumbManager;
	}

	/**
	 * @return \App\ValueObjects\Breadcrumb[]
	 */
	public function getBreadcrumbs() {
		return $this->breadcrumbManager->get();
	}

}