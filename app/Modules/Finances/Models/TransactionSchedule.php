<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $transaction_id
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|TransactionCategory[] $subcategories
 * @property Collection|Transaction[] $transactions
 * @property Transaction $transaction
 */
class TransactionSchedule
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'transaction_id',
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
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function transaction() {
		return $this->hasOne(Transaction::class, 'id', 'transaction_id');
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.TransactionSchedule',
			],

			'flush-tags' => [
				'Finances.TransactionSchedule',
			],
		];
	}

}