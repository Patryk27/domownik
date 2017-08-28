<?php

use App\Services\Breadcrumb\PushHandlerContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\ValueObjects\Breadcrumb as BreadcrumbVO;
use Codeception\Test\Unit as UnitTest;

class BreadcrumbManagerTest
	extends UnitTest {

	/**
	 * Tests if pushUrl() works.
	 */
	public function testPush() {
		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager
			->pushUrl('url-1', 'title-1')
			->pushUrl('url-2', 'title-2')
			->pushUrl('url-3', 'title-3');

		$breadcrumbs =
			$breadcrumbManager
				->getBreadcrumbs()
				->all();

		$this->assertCount(3, $breadcrumbs);

		foreach ($breadcrumbs as $breadcrumb) {
			$this->assertInstanceOf(BreadcrumbVO::class, $breadcrumb);
		}
	}

	/**
	 * Tests if pushCustom() fails with no custom push handler registered at all.
	 */
	public function testPushCustomFails() {
		$this->expectExceptionMessage('No valid custom push handler found for an instance of class [stdClass].');

		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager->push(new stdClass());
	}

	/**
	 * Tests if pushCustom() works properly (including throwing appropriate error messages).
	 */
	public function testPushCustom() {
		$breadcrumbManager = new BreadcrumbManager();
		$breadcrumbManager->registerPushHandler(new class
			implements PushHandlerContract {

			/**
			 * @inheritDoc
			 */
			public function handle($value): ?BreadcrumbVO {
				if ($value instanceof stdClass && isset($value->url, $value->name)) {
					return new BreadcrumbVO($value->url, $value->name);
				}

				return null;
			}

		});

		// prepare and push custom breadcrumb
		$custom = new stdClass();
		$custom->url = 'custom-url';
		$custom->name = 'custom-name';

		$breadcrumbManager->push($custom);

		// check if breadcrumb manager parsed it correctly

		/**
		 * @var BreadcrumbVO[] $breadcrumbs
		 */
		$breadcrumbs =
			$breadcrumbManager
				->getBreadcrumbs()
				->all();

		$this->assertCount(1, $breadcrumbs);
		$this->assertInstanceOf(BreadcrumbVO::class, $breadcrumbs[0]);

		$this->assertEquals($custom->url, $breadcrumbs[0]->getUrl());
		$this->assertEquals($custom->name, $breadcrumbs[0]->getCaption());

		// check if it fails on some other object
		$this->expectExceptionMessage('No valid custom push handler found for an instance of class [stdClass].');
		$breadcrumbManager->push(new stdClass());
	}

}