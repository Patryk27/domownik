<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int id
 * @property int transaction_id
 * @property int day_number
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class TransactionPeriodicityMonthly
	extends Model {

	/**
	 * @var array
	 */
	protected $fillable = ['day_number'];

	/**
	 * @var array
	 */
	public $dates = ['created_at', 'updated_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function transaction() {
		// @todo cache
		return $this->morphToMany(Transaction::class, 'transaction_periodicity');
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.TransactionPeriodicityMonthly',
			],

			'flush-tags' => [
				'Finances.TransactionPeriodicityMonthly',
			],
		];
	}

}
