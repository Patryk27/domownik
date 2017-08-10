<?php

use App\Repositories\Contracts\SettingRepositoryContract;
use App\Services\Configuration\Manager as ConfigurationManager;
use Codeception\Test\Unit as UnitTest;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Foundation\Application;

class ConfigurationManagerTest
	extends UnitTest {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var AuthGuard|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $authGuardMock;

	/**
	 * @var AuthManager|PHPUnit_Framework_MockObject_MockObject
	 */
	protected $authManagerMock;

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

		$this->app = app();

		$this->authGuardMock = $this->createMock(AuthGuard::class);
		$this->authManagerMock = $this->createMock(AuthManager::class);
		$this->settingRepositoryMock = $this->createMock(SettingRepositoryContract::class);

		$this->authManagerMock
			->expects($this->any())
			->method('guard')
			->willReturn($this->authGuardMock);

		$this->app
			->when(ConfigurationManager::class)
			->needs(AuthManager::class)
			->give(function() {
				return $this->authManagerMock;
			});

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
		$this->authGuardMock
			->expects($this->any())
			->method('check')
			->willReturn(true);

		$this->authGuardMock
			->expects($this->any())
			->method('id')
			->willReturn(666);

		$this->settingRepositoryMock
			->expects($this->at(0))
			->method('getUserValueByKey')
			->with(666, 'language')
			->willReturn('en');

		$this->settingRepositoryMock
			->expects($this->at(1))
			->method('getUserValueByKey')
			->with(666, 'ur-mom')
			->willReturn(null);

		$this->assertEquals('en', $this->configurationManager->getValueOrNull('language'));
		$this->assertNull($this->configurationManager->getValueOrNull('ur-mom'));
	}

}