<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;

/**
 * @property int $id
 * @property int $base_budget_id
 * @property int $subject_budget_id
 */
class BudgetConsolidation
	extends Model {

	/**
	 * @var bool
	 */
	public $timestamps = false;

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

}