<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $full_name
 * @property string $remember_token
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
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
		'is_active',
	];

	/**
	 * @var array
	 */
	public $dates = [
		'created_at',
		'updated_at'
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
