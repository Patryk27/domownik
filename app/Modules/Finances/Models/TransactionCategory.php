<?php

namespace App\Modules\Finances\Models;

use App\Models\HasPresenter;
use App\Models\Model;
use App\Modules\Finances\Presenters\TransactionCategoryPresenter;
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

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.TransactionCategory',
			],

			'flush-tags' => [
				'Finances.TransactionCategory',
			],
		];
	}

}