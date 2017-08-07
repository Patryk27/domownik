<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property float $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionValueConstant
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'value',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function transaction() {
		return $this->morphOne(Transaction::class, 'value');
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.Transaction',
				'Finances.TransactionValue',
				'Finances.TransactionValueConstant',
			],

			'flush-tags' => [
				'Finances.TransactionValueConstant',
			],
		];
	}

}
