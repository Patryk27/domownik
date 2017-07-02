<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Transaction[] $transaction
 */
class TransactionPeriodicityOneShot
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'date',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'date',
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
				'Finances.TransactionPeriodicityOneShot',
			],

			'flush-tags' => [
				'Finances.TransactionPeriodicityOneShot',
			],
		];
	}

}
