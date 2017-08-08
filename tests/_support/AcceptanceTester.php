<?php

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester
	extends \Codeception\Actor {

	use _generated\AcceptanceTesterActions;

	/**
	 * @param string $user
	 * @param string $password
	 * @param bool $checkIfSucceeded
	 * @return $this
	 */
	public function loginAs(string $user, string $password, bool $checkIfSucceeded) {
		$I = $this;

		$snapshotName = sprintf('login_%s_%s', $user, $password);

		if ($I->loadSessionSnapshot($snapshotName)) {
			return $this;
		}

		$I->amOnPage('/');
		$I->see('views/dashboard/auth/login.submit');

		$I->fillField('login', $user);
		$I->fillField('password', $password);
		$I->click('views/dashboard/auth/login.submit');

		if ($checkIfSucceeded) {
			$I->see('sidebars/dashboard.system');
		}

		$I->saveSessionSnapshot($snapshotName);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function loginAsAdmin() {
		return $this->loginAs('admin', 'admin', true);
	}

	/**
	 * @return $this
	 */
	public function logout() {
		$this->click('layout.navbar.logout');
		$this->see('requests/auth/logout.messages.success');

		return $this;
	}

}
