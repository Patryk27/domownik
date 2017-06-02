<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionPeriodicityDaily
	extends Model {

	/**
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function transaction() {
		return $this->morphToMany(Transaction::class, 'transaction_periodicity');
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.TransactionPeriodicityDaily',
			],

			'flush-tags' => [
				'Finances.TransactionPeriodicityDaily',
			],
		];
	}

}
