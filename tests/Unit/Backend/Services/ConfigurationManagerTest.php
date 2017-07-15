<?php

use App\Repositories\Contracts\SettingRepositoryContract;
use App\Repositories\Eloquent\SettingRepository;
use App\Services\Configuration\Manager as ConfigurationManager;
use Tests\Unit\TestCase;

class ConfigurationManagerTest
	extends TestCase {

	/**
	 * @var SettingRepositoryContract|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $settingRepositoryMock;

	/**
	 * @var ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();

		$this->settingRepositoryMock = $this->createMock(SettingRepository::class);

		$this->app
			->when(ConfigurationManager::class)
			->needs(SettingRepositoryContract::class)
			->give(function() {
				return $this->settingRepositoryMock;
			});

		$this->configurationManager = $this->app->make(ConfigurationManager::class);
	}

	/**
	 * Tests getValueOrNull() in an anonymous context.
	 */
	public function testGetValueOrNullAnonymous() {
		$this->settingRepositoryMock
			->expects($this->at(0))
			->method('getValueByKey')
			->with('language')
			->willReturn('pl');

		$this->settingRepositoryMock
			->expects($this->at(1))
			->method('getValueByKey')
			->with('ur-mom')
			->willReturn(null);

		$this->assertEquals('pl', $this->configurationManager->getValueOrNull('language'));
		$this->assertNull($this->configurationManager->getValueOrNull('ur-mom'));
	}

	/**
	 * Tests getValueOrFail() in an anonymous context.
	 */
	public function testGetValueOrFailAnonymous() {
		$this->settingRepositoryMock
			->expects($this->at(0))
			->method('getValueByKey')
			->with('language')
			->willReturn('pl');

		$this->settingRepositoryMock
			->expects($this->at(1))
			->method('getValueByKey')
			->with('ur-mom')
			->willReturn(null);

		$this->assertEquals('pl', $this->configurationManager->getValueOrFail('language'));

		$this->expectExceptionMessage('Configuration key not found: ur-mom.');
		$this->configurationManager->getValueOrFail('ur-mom');
	}

	/**
	 * Tests getValueOrDefault() in an anonymous context.
	 */
	public function testGetValueOrDefaultAnonymous() {
		$this->settingRepositoryMock
			->expects($this->at(0))
			->method('getValueByKey')
			->with('language')
			->willReturn(null);

		$this->assertNotNull($this->configurationManager->getValueOrDefault('language'));
	}

	/**
	 * Tests getValueOrNull() in an authorized context.
	 */
	public function testGetValueOrNullAuthorized() {
		$this->be($sampleUser = $this->getSampleUser());

		$this->settingRepositoryMock
			->expects($this->at(0))
			->method('getUserValueByKey')
			->with($sampleUser->id, 'language')
			->willReturn('en');

		$this->settingRepositoryMock
			->expects($this->at(1))
			->method('getUserValueByKey')
			->with($sampleUser->id, 'ur-mom')
			->willReturn(null);

		$this->assertEquals('en', $this->configurationManager->getValueOrNull('language'));
		$this->assertNull($this->configurationManager->getValueOrNull('ur-mom'));
	}

}