<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard as AuthGuard;
use Illuminate\Contracts\Auth\StatefulGuard as AuthStatefulGuard;

class AuthController
	extends BaseController {

	/**
	 * @var AuthManager
	 */
	protected $authManager;

	/**
	 * @var AuthGuard|AuthStatefulGuard
	 */
	protected $authGuard;

	/**
	 * @param AuthManager $authManager
	 */
	public function __construct(
		AuthManager $authManager
	) {
		$this->authManager = $authManager;
		$this->authGuard = $authManager->guard();
	}

	/**
	 * @return mixed
	 */
	public function login() {
		if ($this->authGuard->check()) {
			return redirect(route('dashboard.index.index'));
		}

		return view('views.dashboard.auth.login');
	}

	/**
	 * @param AuthLoginRequest $request
	 * @return mixed
	 */
	public function postLogin(AuthLoginRequest $request) {
		$rememberMe = (bool)$request->get('remember-me', false);

		$authSuccess = $this->authGuard->attempt([
			'login' => $request->get('login'),
			'password' => $request->get('password'),
			'status' => User::STATUS_ACTIVE,
		], $rememberMe);

		if ($authSuccess) {
			return redirect()->route('dashboard.index.index');
		} else {
			flash(__('requests/user/login.messages.invalid-credentials'), 'danger');

			return
				redirect()
					->route('dashboard.auth.login')
					->withInput();
		}
	}

	/**
	 * @return mixed
	 */
	public function logout() {
		if ($this->authGuard->check()) {
			$this->authGuard->logout();

			flash(__('requests/user/logout.messages.success'), 'success');
		}

		return redirect()->intended(route('dashboard.auth.login'));
	}

}