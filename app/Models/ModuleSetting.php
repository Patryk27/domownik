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
	 * @param int $moduleId
	 * @param string $key
	 * @return mixed|null
	 */
	public static function getSettingValue($moduleId, $key) {
		// @todo cache

		$query = ModuleSetting::where([
			'module_id' => $moduleId,
			'key' => $key,
		]);

		$moduleSetting = $query->first();

		if (is_null($moduleSetting)) {
			return null;
		} else {
			return $moduleSetting->value;
		}
	}

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
