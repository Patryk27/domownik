<?php

namespace App\Models;

use App\Presenters\TransactionCategoryPresenter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $parent_category_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection|TransactionCategory[] $subcategories
 * @property Collection|Transaction[] $transactions
 * @method TransactionCategoryPresenter getPresenter()
 * @property string $full_name // set by the presenter
 */
class TransactionCategory
	extends Model {

	use HasPresenter;

	/**
	 * @var array
	 */
	public $fillable = [
		'parent_category_id',
		'name',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * @var string
	 */
	public $presenterClass = TransactionCategoryPresenter::class;

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.Transaction',
				'Finances.TransactionCategory',
			],

			'flush-tags' => [
				'Finances.Transaction',
				'Finances.TransactionCategory',
			],
		];
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subcategories() {
		return $this->hasMany(self::class, 'parent_category_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function transactions() {
		return $this->hasMany(Transaction::class, 'category_id');
	}

}