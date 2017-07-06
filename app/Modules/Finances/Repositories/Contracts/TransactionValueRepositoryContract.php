<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Models\Model;

interface TransactionValueRepositoryContract {

	/**
	 * @param Model $transactionValue
	 * @return TransactionValueRepositoryContract
	 */
	public function persist(Model $transactionValue): TransactionValueRepositoryContract;

}