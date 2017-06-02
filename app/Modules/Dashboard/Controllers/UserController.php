<?php

namespace App\Modules\Dashboard\Controllers;

use App\Modules\Dashboard\Http\Requests\User\LoginRequest;
use Auth;
use Illuminate\Http\Request;

class UserController
	extends \App\Http\Controllers\Controller {

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
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionPostLogin(LoginRequest $request) {
		$rememberMe = (bool)$request->get('remember-me', false);

		$authSuccess = Auth::attempt([
			'login' => $request->get('login'),
			'password' => $request->get('password'),
			'is_active' => 1,
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

}
