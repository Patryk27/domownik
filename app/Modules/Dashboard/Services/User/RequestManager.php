<?php

namespace App\Modules\Dashboard\Services\User;

use App\Models\User;
use App\Modules\Dashboard\Http\Requests\User\StoreRequest as UserStoreRequest;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

class RequestManager
	implements RequestManagerContract {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var UserRepositoryContract
	 */
	protected $userRepository;

	/**
	 * @var UserStoreRequest
	 */
	protected $request;

	/**
	 * @var User|null
	 */
	protected $model;

	/**
	 * @param LoggerContract $logger
	 * @param DatabaseConnection $databaseConnection
	 * @param UserRepositoryContract $userRepository
	 */
	public function __construct(
		LoggerContract $logger,
		DatabaseConnection $databaseConnection,
		UserRepositoryContract $userRepository
	) {
		$this->logger = $logger;
		$this->databaseConnection = $databaseConnection;
		$this->userRepository = $userRepository;
	}

	/**
	 * @inheritdoc
	 */
	public function store(UserStoreRequest $request): string {
		$this->request = $request;
		$this->model = null;

		return $this->databaseConnection->transaction(function() {
			if ($this->request->has('userId')) {
				$this->update();
				return BaseRequestManagerContract::STORE_RESULT_UPDATED;
			} else {
				$this->insert();
				return BaseRequestManagerContract::STORE_RESULT_CREATED;
			}
		});
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $userId): RequestManagerContract {
		$this->logger->info('Deleting user with id=%d.', $userId);

		$this->model = $this->userRepository->getOrFail($userId);
		$this->userRepository->delete($this->model->id);

		/**
		 * @todo What if user is logged during the deletion?
		 */

		return $this;
	}

	/**
	 * @return User
	 */
	public function getModel(): User {
		return $this->model;
	}

	/**
	 * @return RequestManager
	 */
	protected function update(): self {
		$this->logger->info('Updating user with id=%d: %s', $this->request->get('userId'), $this->request);

		$user = $this->userRepository->getOrFail($this->request->get('userId'));
		$user->login = $this->request->get('userLogin');
		$user->full_name = $this->request->get('userFullName');
		$user->status = $this->request->get('userStatus');

		if ($this->request->has('userPassword')) {
			$user->password = bcrypt($this->request->get('userPassword'));
		}

		$this->model = $user;
		$this->userRepository->persist($user);

		return $this;
	}

	/**
	 * @return RequestManager
	 */
	protected function insert(): self {
		$this->logger->info('Creating new user: %s', $this->request);

		$user = new User();
		$user->login = $this->request->get('userLogin');
		$user->full_name = $this->request->get('userFullName');
		$user->password = bcrypt($this->request->get('userPassword'));
		$user->status = $this->request->get('userStatus');

		$this->model = $user;
		$this->userRepository->persist($user);

		return $this;
	}

}