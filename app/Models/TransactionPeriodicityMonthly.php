<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property int $transaction_id
 * @property int $day
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionPeriodicityMonthly
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'day',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'created_at',
		'updated_at',
	];

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
				'Finances.Transaction',
				'Finances.TransactionPeriodicity',
			],

			'flush-tags' => [
				'Finances.TransactionPeriodicity',
			],
		];
	}

}
