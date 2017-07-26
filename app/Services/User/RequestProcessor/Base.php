<?php

namespace App\Services\User\RequestProcessor;

use App\Http\Requests\User\CrudRequest as UserCrudRequest;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Database\Connection as DatabaseConnection;

abstract class Base {

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
	 * @param UserCrudRequest $request
	 * @return User
	 */
	protected function getUserFromRequest(UserCrudRequest $request): User {
		$user = new User();
		$user->login = $request->get('login');
		$user->full_name = $request->get('full_name');

		if ($request->has('password')) {
			$user->password = bcrypt($request->get('password'));
		}

		$user->status = $request->get('status');

		return $user;
	}

}