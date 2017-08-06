<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Budget
	extends Model {

	use HasPresenter;

	const
		TYPE_REGULAR = 'regular',
		TYPE_CONSOLIDATED = 'consolidated';

	const
		STATUS_ACTIVE = 'active',
		STATUS_ARCHIVED = 'archived';

	/**
	 * @var array
	 */
	public $fillable = [
		'type',
		'name',
		'description',
		'status',
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
	public $presenterClass = \App\Presenters\BudgetPresenter::class;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function consolidatedBudgets() {
		return $this->belongsToMany(self::class, 'budget_consolidations', 'base_budget_id', 'subject_budget_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function transactions() {
		return $this->morphMany(Transaction::class, 'parent');
	}

	/**
	 * @return string[]
	 */
	public static function getTypes(): array {
		return [
			self::TYPE_REGULAR,
			self::TYPE_CONSOLIDATED,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getTypesSelect(): array {
		return map_translation(self::getTypes(), 'models/budget.enums.types.%s');
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.Budget',
			],

			'flush-tags' => [
				'Finances.Budget',
			],
		];
	}

}
