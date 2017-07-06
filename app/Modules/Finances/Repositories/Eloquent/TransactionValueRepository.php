<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Models\Model;
use App\Modules\Finances\Repositories\Contracts\TransactionValueRepositoryContract;

class TransactionValueRepository
	implements TransactionValueRepositoryContract {

	/**
	 * @inheritdoc
	 */
	public function persist(Model $transactionValue): TransactionValueRepositoryContract {
		$transactionValue->saveOrFail();
		
		$transactionValue::getFlushCache()
						 ->flush();

		return $this;
	}

}