<?php

class AuthorizationCest {

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function loginAsAdminAndFail(AcceptanceTester $I): void {
		$I->wantTo('Log in as \'admin\' using invalid password add see if it fails.');

		$I->loginAs('admin', 'invalid-password', false);
		$I->see('requests/auth/login.messages.invalid-credentials');
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function loginAsAdminAndSucceed(AcceptanceTester $I): void {
		$I->wantTo('Log in as \'admin\' using valid password and check if it succeeds.');

		$I->loginAsAdmin();
	}

}