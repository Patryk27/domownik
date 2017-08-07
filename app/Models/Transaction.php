<?php

namespace App\Models;

use App\Presenters\TransactionPresenter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $parent_transaction_id
 * @property string $parent_type
 * @property int $parent_id
 * @property int $category_id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property int $value_id
 * @property string $value_type
 * @property string $periodicity_type
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Model $value
 * @property TransactionCategory $category
 * @property mixed $periodicity
 * @property Collection|TransactionPeriodicityOneShot[] $periodicityOneShots
 * @property Collection|TransactionPeriodicityDaily[] $periodicityDailies
 * @property Collection|TransactionPeriodicityWeekly[] $periodicityWeeklies
 * @property Collection|TransactionPeriodicityMonthly[] $periodicityMonthlies
 * @property Collection|TransactionPeriodicityYearly[] $periodicityYearlies
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

	/**
	 * @var string
	 */
	public $presenterClass = TransactionPresenter::class;

	/**
	 * @var array
	 */
	public $fillable = [
		'parent_transaction_id',
		'parent_id',
		'parent_type',
		'category_id',
		'type',
		'name',
		'description',
		'value_id',
		'value_type',
		'periodicity_type',
	];

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
	public function periodicityDaily() {
		return $this->morphedByMany(TransactionPeriodicityDaily::class, 'transaction_periodicity');
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
	 * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
	 */
	public function periodicityYearlies() {
		return $this->morphedByMany(TransactionPeriodicityYearly::class, 'transaction_periodicity');
	}

	/**
	 * @return string[]
	 */
	public static function getTypes(): array {
		return [
			self::TYPE_INCOME,
			self::TYPE_EXPENSE,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getTypesSelect(): array {
		return map_translation(self::getTypes(), 'models/transaction.enums.types.%s');
	}

	/**
	 * @return string[]
	 */
	public static function getValueTypes(): array {
		return [
			self::VALUE_TYPE_CONSTANT,
			self::VALUE_TYPE_RANGE,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getValueTypesSelect(): array {
		return map_translation(self::getValueTypes(), 'models/transaction.enums.value-types.%s');
	}

	/**
	 * @return string[]
	 */
	public static function getPeriodicityTypes(): array {
		return [
			self::PERIODICITY_TYPE_ONE_SHOT,
			self::PERIODICITY_TYPE_DAILY,
			self::PERIODICITY_TYPE_WEEKLY,
			self::PERIODICITY_TYPE_MONTHLY,
			self::PERIODICITY_TYPE_YEARLY,
		];
	}

	/**
	 * @return string[]
	 */
	public static function getPeriodicityTypesSelect(): array {
		return map_translation(self::getPeriodicityTypes(), 'models/transaction.enums.periodicity-types.%s');
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
