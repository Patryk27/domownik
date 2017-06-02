<?php

namespace App\Models;

class Setting
	extends Model {

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
