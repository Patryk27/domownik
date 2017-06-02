<?php

namespace App\Modules\Finances\Models;

use App\Models\HasPresenter;
use App\Models\Model;
use App\Modules\Finances\Presenters\TransactionPresenter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $parent_transaction_id
 * @property int $parent_id
 * @property string $parent_type
 * @property int $category_id
 * @property TransactionCategory $category
 * @property string $type
 * @property string $name
 * @property string $description
 * @property int $value_id
 * @property string $value_type
 * @property string $periodicity_type
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Model $value
 * @property Collection|TransactionPeriodicityOneShot[] $periodicityOneShots
 * @property Collection|TransactionPeriodicityWeekly[] $periodicityWeeklies
 * @property Collection|TransactionPeriodicityMonthly[] $periodicityMonthlies
 * @method TransactionPresenter getPresenter()
 */
class Transaction
	extends Model {

	use HasPresenter;

	const
		PARENT_TYPE_BUDGET = 'budget',
		PARENT_TYPE_SAVING = 'saving';

	const
		TYPE_INCOME = 'income',
		TYPE_EXPENSE = 'expense';

	const
		VALUE_TYPE_CONSTANT = 'transaction-value-constant',
		VALUE_TYPE_RANGE = 'transaction-value-range';

	const
		PERIODICITY_TYPE_ONE_SHOT = 'transaction-periodicity-one-shot',
		PERIODICITY_TYPE_DAILY = 'transaction-periodicity-daily',
		PERIODICITY_TYPE_WEEKLY = 'transaction-periodicity-weekly',
		PERIODICITY_TYPE_MONTHLY = 'transaction-periodicity-monthly',
		PERIODICITY_TYPE_YEARLY = 'transaction-periodicity-yearly';

	public $presenterClass = TransactionPresenter::class;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function parent() {
		return $this->morphTo();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function category() {
		return $this->hasOne(TransactionCategory::class, 'category_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function value() {
		return $this->morphTo();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function periodicity() {
		return $this->morphTo();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function periodicityOneShots() {
		return $this->morphedByMany(TransactionPeriodicityOneShot::class, 'transaction_periodicity');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function periodicityWeeklies() {
		return $this->morphedByMany(TransactionPeriodicityWeekly::class, 'transaction_periodicity');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function periodicityMonthlies() {
		return $this->morphedByMany(TransactionPeriodicityMonthly::class, 'transaction_periodicity');
	}

	/**
	 * @return string[]
	 */
	public static function getTypes() {
		return [
			self::TYPE_INCOME,
			self::TYPE_EXPENSE,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getValueTypes() {
		return [
			self::VALUE_TYPE_CONSTANT,
			self::VALUE_TYPE_RANGE,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getPeriodicityTypes() {
		return [
			self::PERIODICITY_TYPE_ONE_SHOT,
			self::PERIODICITY_TYPE_DAILY,
			self::PERIODICITY_TYPE_WEEKLY,
			self::PERIODICITY_TYPE_MONTHLY,
			self::PERIODICITY_TYPE_YEARLY,
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Finances',
				'Finances.Transaction',
			],

			'flush-tags' => [
				'Finances.Transaction',
			],
		];
	}

}
