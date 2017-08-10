<?php

use Codeception\Util\Locator;

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
		$I->wantTo('Check if \'Edit user\' button works.');

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

		// check breadcrumbs
		$I->see('breadcrumbs.users.index');
		$I->see('breadcrumbs.users.create');

		// submit form and check confirmation message
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
	 * @depends createTestUser
	 */
	public function loginAsTest(AcceptanceTester $I): void {
		$I->wantTo('Login as the \'test\' user.');

		$I->loginAs('test', 'test-password', true);
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 * @depends loginAsTest
	 */
	public function editTestUser(AcceptanceTester $I): void {
		$I->wantTo('Edit the \'test\' user\'s full name.');

		$I->loginAs('test', 'test-password', true);
		$I->amOnPage('/dashboard/users');

		// click the last 'edit' button and check if it points to a valid site
		$I->click('table tbody tr:nth-child(2) .btn-primary');
		$I->canSeeInCurrentUrl('/dashboard/users/2/edit');

		// check breadcrumbs
		$I->see('breadcrumbs.users.index');
		$I->see('breadcrumbs.users.edit');

		// change user name, asserting current name is valid
		$I->seeInField('[name="full_name"]', 'Test Test');
		$I->fillField('full_name', 'Testing User');

		// save form and check for the confirmation message
		$I->click('components/form.buttons.save');
		$I->waitForElement('.alert');
		$I->see('requests/user/crud.messages.updated');

		// assure field has changed
		$I->seeInField('[name="full_name"]', 'Testing User');
	}

	/**
	 * @param AcceptanceTester $I
	 * @return void
	 * @depends loginAsTest
	 */
	public function deleteTestUser(AcceptanceTester $I): void {
		$I->wantTo('Delete the \'test\' user.');

		$I->loginAsAdmin();
		$I->amOnPage('/dashboard/users/2/edit');

		// click the 'Delete' button and confirm
		$I->click(Locator::contains('a', 'components/form.buttons.delete'));
		$I->waitForText('requests/user/crud.confirms.delete');
		$I->click(Locator::find('button', ['data-bb-handler' => 'confirm']));

		// check confirmation message
		$I->waitForElement('.alert');
		$I->see('requests/user/crud.messages.deleted');
	}

}