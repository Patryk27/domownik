<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\User\Crud\StoreRequest as UserStoreRequest;
use App\Http\Requests\User\Crud\UpdateRequest as UserUpdateRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Breadcrumb\ManagerContract as BreadcrumbManagerContract;
use App\Services\User\Request\ProcessorContract as UserRequestProcessorContract;

class UserController
	extends BaseController {

	/**
	 * @var BreadcrumbManagerContract
	 */
	protected $breadcrumbManager;

	/**
	 * @var UserRepositoryContract
	 */
	protected $userRepository;

	/**
	 * @var UserRequestProcessorContract
	 */
	protected $userRequestProcessor;

	/**
	 * @param BreadcrumbManagerContract $breadcrumbManager
	 * @param UserRepositoryContract $userRepository
	 * @param UserRequestProcessorContract $userRequestProcessor
	 */
	public function __construct(
		BreadcrumbManagerContract $breadcrumbManager,
		UserRepositoryContract $userRepository,
		UserRequestProcessorContract $userRequestProcessor
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
		$this->breadcrumbManager->pushUrl(route('dashboard.users.index'), __('breadcrumbs.users.index'));
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
			->pushUrl(route('dashboard.users.index'), __('breadcrumbs.users.index'))
			->pushUrl(route('dashboard.users.create'), __('breadcrumbs.users.create'));

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

		$this->putFlash('success', __('requests/user/crud.messages.stored'));

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
			->pushUrl(route('dashboard.users.index'), __('breadcrumbs.users.index'))
			->push($user);

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

		$this->putFlash('success', __('requests/user/crud.messages.updated'));

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

		$this->putFlash('success', __('requests/user/crud.messages.deleted'));

		return response()->json([
			'redirectUrl' => route('dashboard.users.index'),
		]);
	}

}
