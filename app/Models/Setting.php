<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property int|null $userId
 * @property string $key
 * @property mixed $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Setting
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'user_id',
		'key',
		'value',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'created_at',
		'updated_at',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		'value' => 'json',
	];

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Base',
				'Base.Setting',
			],

			'flush-tags' => [
				'Base.Setting',
			],
		];
	}

}
