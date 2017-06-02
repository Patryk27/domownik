<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionPeriodicityOneShot
	extends Model {

	/**
	 * @var array
	 */
	protected $fillable = ['date'];

	/**
	 * @var array
	 */
	protected $dates = ['date', 'created_at', 'updated_at'];

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
				'Finances.TransactionPeriodicityOneShot',
			],

			'flush-tags' => [
				'Finances.TransactionPeriodicityOneShot',
			],
		];
	}

}
