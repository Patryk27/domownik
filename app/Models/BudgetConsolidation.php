<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $base_budget_id
 * @property int $subject_budget_id
 */
class BudgetConsolidation
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'base_budget_id',
		'subject_budget_id',
	];

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.BudgetConsolidation',
			],

			'flush-tags' => [
				'Finances.BudgetConsolidation',
			],
		];
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function baseBudget() {
		return $this->hasOne(Budget::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function subjectBudget() {
		return $this->hasOne(Budget::class);
	}

}