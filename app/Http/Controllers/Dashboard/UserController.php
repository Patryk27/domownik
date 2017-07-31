<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\User\Crud\StoreRequest as UserStoreRequest;
use App\Http\Requests\User\Crud\UpdateRequest as UserUpdateRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\User\RequestProcessorContract as UserRequestManagerContract;

class UserController
	extends BaseController {

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
	protected $userRequestProcessor;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param UserRepositoryContract $userRepository
	 * @param UserRequestManagerContract $userRequestProcessor
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		UserRepositoryContract $userRepository,
		UserRequestManagerContract $userRequestProcessor
	) {
		// @todo ACL (root-only controller)

		$this->breadcrumbManager = $breadcrumbManager;
		$this->userRepository = $userRepository;
		$this->userRequestProcessor = $userRequestProcessor;
	}

	/**
	 * @return mixed
	 */
	public function index() {
		$this->breadcrumbManager->push(route('dashboard.users.index'), __('breadcrumbs.users.index'));
		$users = $this->userRepository->getAll();

		return view('views.dashboard.users.index', [
			'users' => $users,
		]);
	}

	/**
	 * @return mixed
	 */
	public function create() {
		$this->breadcrumbManager
			->push(route('dashboard.users.index'), __('breadcrumbs.users.index'))
			->push(route('dashboard.users.create'), __('breadcrumbs.users.create'));

		return view('views.dashboard.users.create', [
			'user' => null,

			'form' => [
				'url' => route('dashboard.users.store'),
				'method' => 'post',
			],
		]);
	}

	/**
	 * @param UserStoreRequest $request
	 * @return mixed
	 */
	public function store(UserStoreRequest $request) {
		$result = $this->userRequestProcessor->store($request);
		$user = $result->getUser();

		flash(__('requests/user/crud.messages.stored'), 'success');

		return response()->json([
			'redirectUrl' => route('dashboard.users.edit', $user->id),
		]);
	}

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function show(User $user) {
		return redirect()->route('dashboard.users.edit', $user);
	}

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function edit(User $user) {
		$this->breadcrumbManager
			->push(route('dashboard.users.index'), __('breadcrumbs.users.index'))
			->pushCustom($user);

		return view('views.dashboard.users.edit', [
			'user' => $user,

			'form' => [
				'url' => route('dashboard.users.update', $user->id),
				'method' => 'put',
			],
		]);
	}

	/**
	 * @param UserUpdateRequest $request
	 * @param int $id
	 * @return mixed
	 */
	public function update(UserUpdateRequest $request, int $id) {
		$result = $this->userRequestProcessor->update($request, $id);
		$user = $result->getUser();

		flash(__('requests/user/crud.messages.updated'), 'success');

		return response()->json([
			'redirectUrl' => route('dashboard.users.edit', $user->id),
		]);
	}

	/**
	 * @param User $user
	 * @return mixed
	 */
	public function destroy(User $user) {
		$this->userRequestProcessor->delete($user->id);

		flash(__('requests/user/crud.messages.deleted'), 'success');

		return response()->json([
			'redirectUrl' => route('dashboard.users.index'),
		]);
	}

}
