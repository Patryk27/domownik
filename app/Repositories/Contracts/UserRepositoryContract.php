<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * @method User|null get(int $id, array $columns = ['*'])
 * @method User getOrFail(int $id, array $columns = ['*'])
 * @method Collection|User[] getBy(string $fieldName, $fieldValue, array $columns = ['*'])
 */
interface UserRepositoryContract
	extends CrudRepositoryContract {

}