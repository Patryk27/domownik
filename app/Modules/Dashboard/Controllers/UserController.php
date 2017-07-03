<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Dashboard\Http\Requests\User\LoginRequest as UserLoginRequest;
use App\Modules\Dashboard\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\Modules\Dashboard\Services\User\RequestManagerContract as UserRequestManagerContract;
use App\Repositories\Contracts\UserRepositoryContract;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use Auth;

class UserController
	extends Controller {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var UserRepositoryContract
	 */
	protected $userRepository;

	/**
	 * @var UserRequestManagerContract
	 */
	protected $userRequestManager;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param UserRepositoryContract $userRepository
	 * @param UserRequestManagerContract $userRequestManager
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		UserRepositoryContract $userRepository,
		UserRequestManagerContract $userRequestManager
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->userRepository = $userRepository;
		$this->userRequestManager = $userRequestManager;
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionLogin() {
		if (Auth::check()) {
			return redirect(route('dashboard.index.index'));
		}

		return view('Dashboard::user/login');
	}

	/**
	 * @param UserLoginRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionPostLogin(UserLoginRequest $request) {
		$rememberMe = (bool)$request->get('remember-me', false);

		$authSuccess = Auth::attempt([
			'login' => $request->get('login'),
			'password' => $request->get('password'),
			'status' => User::STATUS_ACTIVE,
		], $rememberMe);

		if ($authSuccess) {
			return redirect()->route('dashboard.index.index');
		} else {
			flash(__('Dashboard::requests/user/login.messages.invalid-credentials'), 'danger');

			return
				redirect()
					->route('dashboard.user.login')
					->withInput();
		}
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionLogout() {
		if (Auth::check()) {
			Auth::logout();
			flash(__('Dashboard::requests/user/logout.messages.success'), 'success');
		}

		return redirect()->intended(route('dashboard.user.login'));
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionList() {
		// @todo ACL (root-only section)

		$this->breadcrumbManager->push(route('dashboard.user.list'), __('Dashboard::breadcrumb.user.list'));

		return view('Dashboard::user/list', [
			'users' => $this->userRepository->getAll(),
		]);
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionCreate() {
		$this->breadcrumbManager
			->push(route('dashboard.user.list'), __('Dashboard::breadcrumb.user.list'))
			->push(route('dashboard.user.create'), __('Dashboard::breadcrumb.user.create'));

		return view('Dashboard::user/create');
	}

	/**
	 * @param User $user
	 * @return \Illuminate\Http\Response
	 */
	public function actionEdit(User $user) {
		// @todo ACL

		$this->breadcrumbManager
			->push(route('dashboard.user.list'), __('Dashboard::breadcrumb.user.list'))
			->pushCustom($user);

		return view('Dashboard::user/edit', [
			'user' => $user,
		]);
	}

	/**
	 * @param UserStoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionStore(UserStoreRequest $request) {
		switch ($this->userRequestManager->store($request)) {
			case BaseRequestManagerContract::STORE_RESULT_CREATED:
				flash(__('Dashboard::requests/user/store.messages.create-success'), 'success');
				break;

			case BaseRequestManagerContract::STORE_RESULT_UPDATED:
				flash(__('Dashboard::requests/user/store.messages.update-success'), 'success');
				break;
		}

		$user = $this->userRequestManager->getModel();

		return response()->json([
			'redirectUrl' => route('dashboard.user.edit', $user->id),
		]);
	}

	/**
	 * @param User $user
	 * @return \Illuminate\Http\Response
	 */
	public function actionDelete(User $user) {
		$this->userRequestManager->delete($user->id);

		flash(__('Dashboard::requests/user/store.messages.delete-success'), 'success');

		return response()->redirectTo(route('dashboard.user.list'));
	}

}
