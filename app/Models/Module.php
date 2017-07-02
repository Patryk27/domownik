<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 */
class Module
	extends Model {

	/**
	 * @var array
	 */
	public $fillable = [
		'name',
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
				'Base',
				'Base.Module',
			],

			'flush-tags' => [
				'Base.Module',
			],
		];
	}

}
