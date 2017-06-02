<?php

namespace App\Modules\Finances\Models;

use App\Models\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string $description
 * @property string $status
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Budget
	extends Model {

	const
		TYPE_REGULAR = 'regular',
		TYPE_CONSOLIDATED = 'consolidated';

	const
		STATUS_ACTIVE = 'active',
		STATUS_ARCHIVED = 'archived';

	/**
	 * @var array
	 */
	public $dates = ['created_at', 'updated_at'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function transactions() {
		return $this->morphMany(Transaction::class, 'parent');
	}

	/**
	 * @return string[]
	 */
	public static function getTypes() {
		return [
			self::TYPE_REGULAR,
			self::TYPE_CONSOLIDATED
		];
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
