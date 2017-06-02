<?php

namespace App\Models;

class ModuleSetting
	extends Model {

	/**
	 * @var string[]
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
		$query = ModuleSetting::where([
			'module_id' => $moduleId,
			'key' => $key
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
