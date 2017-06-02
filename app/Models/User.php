<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class User
	extends Model
	implements Authenticatable {

	use AuthenticableTrait;

	/**
	 * @var string[]
	 */
	protected $fillable = [
		'login',
		'password',
		'full_name',
		'mail',
		'is_active',
	];

	/**
	 * @var string[]
	 */
	protected $hidden = [
		'password',
	];

	/**
	 * @inheritDoc
	 */
	public static function getCacheConfiguration(): array {
		return [
			'tags' => [
				'Base',
				'Base.User',
			],

			'flush-tags' => [
				'Base.User',
			],
		];
	}

}
