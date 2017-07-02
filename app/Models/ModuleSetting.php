<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $id
 * @property int $module_id
 * @property string $key
 * @property mixed $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ModuleSetting
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'module_id',
		'key',
		'value',
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
				'Base.ModuleSetting',
			],

			'flush-tags' => [
				'Base.ModuleSetting',
			],
		];
	}

}
