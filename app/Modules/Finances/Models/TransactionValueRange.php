<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property float $value_from
 * @property float $value_to
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TransactionValueRange
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = ['value_from', 'value_to'];

	/**
	 * @var array
	 */
	public $dates = ['created_at', 'updated_at'];

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
				'Finances.TransactionValueRange',
			],

			'flush-tags' => [
				'Finances.TransactionValueRange',
			],
		];
	}

}
