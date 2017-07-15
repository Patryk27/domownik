<?php

use App\Services\Breadcrumb\CustomPushHandlerContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\ValueObjects\Breadcrumb as BreadcrumbVO;
use Tests\Unit\TestCase;

class BreadcrumbManagerTest
	extends TestCase {

	/**
	 * Tests if basic push() works.
	 */
	public function testPush() {
		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager
			->push('url-1', 'title-1')
			->push('url-2', 'title-2')
			->push('url-3', 'title-3');

		$this->assertEquals(3, $breadcrumbManager->count());

		$breadcrumbs = (array)$breadcrumbManager->get();
		$this->assertCount(3, $breadcrumbs);

		foreach ($breadcrumbs as $breadcrumb) {
			$this->assertInstanceOf(BreadcrumbVO::class, $breadcrumb);
		}
	}

	/**
	 * Tests if pushCustom() fails with no custom push handler registered at all.
	 */
	public function testPushCustomFails() {
		$this->expectExceptionMessage('No valid custom push handler found.');

		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager->pushCustom(new \stdClass());
	}

	/**
	 * Tests if pushCustom() works properly (including throwing appropriate error messages).
	 */
	public function testPushCustom() {
		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager->registerCustomPushHandler(new class
			implements CustomPushHandlerContract {

			/**
			 * @inheritDoc
			 */
			public function getBreadcrumb($custom) {
				if ($custom instanceof \stdClass && isset($custom->url, $custom->name)) {
					return new BreadcrumbVO($custom->url, $custom->name);
				}

				return null;
			}

		});

		// prepare and push custom breadcrumb
		$custom = new \stdClass();
		$custom->url = 'custom-url';
		$custom->name = 'custom-name';

		$breadcrumbManager->pushCustom($custom);

		// check if breadcrumb manager parsed it correctly
		$breadcrumbs = $breadcrumbManager->get();

		$this->assertCount(1, $breadcrumbs);
		$this->assertInstanceOf(BreadcrumbVO::class, $breadcrumbs[0]);

		$this->assertEquals($custom->url, $breadcrumbs[0]->getUrl());
		$this->assertEquals($custom->name, $breadcrumbs[0]->getName());

		// check if it fails on some other object
		$this->expectExceptionMessage('No valid custom push handler found.');
		$breadcrumbManager->pushCustom(new \stdClass());
	}

}