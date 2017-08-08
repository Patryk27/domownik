<?php

class UserManagementCest {

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function checkCreateUserButton(AcceptanceTester $I): void {
		$I->wantTo('Check if \'Create user\' button works.');

		$I->loginAsAdmin();
		$I->amOnPage('/dashboard/users');
		$I->click('views/dashboard/users/index.create-user');
		$I->canSeeCurrentUrlEquals('/dashboard/users/create');
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function checkEditUserButton(AcceptanceTester $I): void {
		$I->wantTo('Check if \'Edit user\' button works for first user on the list.');

		$I->loginAsAdmin();
		$I->amOnPage('/dashboard/users');
		$I->click('views/dashboard/users/index.users-table.body.btn-edit');
		$I->canSeeCurrentUrlEquals('/dashboard/users/1/edit');
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function createTestUser(AcceptanceTester $I): void {
		$I->wantTo('Create a new \'test\' user.');

		$I->loginAsAdmin();
		$I->amOnPage('/dashboard/users/create');
		$I->submitForm('#content form', [
			'login' => 'test',
			'full_name' => 'Test Test',
			'password' => 'test-password',
			'password_confirm' => 'test-password',
			'status' => 'active',
		]);

		$I->waitForElement('.alert');

		$I->see('requests/user/crud.messages.stored');
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function loginAsTest(AcceptanceTester $I): void {
		$I->wantTo('Login as the \'test\' user.');

		$I->loginAs('test', 'test-password', true);
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 */
	public function editTestUser(AcceptanceTester $I): void {
		$I->wantTo('Edit the \'test\' user\'s full name.');

		$I->loginAs('test', 'test-password', true);
		$I->amOnPage('/dashboard/users/2/edit');
		$I->seeInField('[name="full_name"]', 'Test Test');
		$I->fillField('full_name', 'Testing User');
		$I->click('components/form.buttons.save');

		$I->waitForElement('.alert');

		$I->see('requests/user/crud.messages.updated');
		$I->seeInField('[name="full_name"]', 'Testing User');
	}

}