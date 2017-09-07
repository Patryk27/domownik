<?php

namespace App\Services\User;

use App\Http\Requests\User\Crud\Request as UserCrudRequest;
use App\Http\Requests\User\Crud\StoreRequest as UserStoreRequest;
use App\Http\Requests\User\Crud\UpdateRequest as UserUpdateRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\ValueObjects\Requests\User\StoreResult as UserStoreResult;
use App\ValueObjects\Requests\User\UpdateResult as UserUpdateResult;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var UserRepositoryContract
	 */
	protected $userRepository;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param UserRepositoryContract $userRepository
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		UserRepositoryContract $userRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->userRepository = $userRepository;
	}

	/**
	 * @inheritdoc
	 */
	public function store(UserStoreRequest $request): UserStoreResult {
		return $this->db->transaction(function () use ($request) {
			$user = new User();
			$this->fillUser($user, $request);

			$this->log->info('Creating new user [login=%s].', $user->login);
			$this->userRepository->persist($user);
			$this->log->info('... assigned user id: [%d].', $user->id);

			return new UserStoreResult($user);
		});
	}

	/**
	 * @inheritdoc
	 */
	public function update(UserUpdateRequest $request, int $id): UserUpdateResult {
		return $this->db->transaction(function () use ($request, $id) {
			$this->log->info('Updating user [id=%d].', $id);

			$user = $this->userRepository->getOrFail($id);
			$this->fillUser($user, $request);

			$this->userRepository->persist($user);

			return new UserUpdateResult($user);
		});
	}

	/**
	 * @inheritdoc
	 */
	public function delete(int $id): void {
		$this->db->transaction(function () use ($id) {
			$this->log->info('Deleting user [id=%d].', $id);
			$this->userRepository->delete($id);
		});
	}

	/**
	 * @param User $user
	 * @param UserCrudRequest $request
	 * @return $this
	 */
	protected function fillUser(User $user, UserCrudRequest $request) {
		$user->fill([
			'login' => $request->get('login'),
			'full_name' => $request->get('full_name'),
			'status' => $request->get('status'),
		]);

		if ($request->has('password')) {
			$user->password = bcrypt($request->get('password'));
		}

		return $this;
	}

}