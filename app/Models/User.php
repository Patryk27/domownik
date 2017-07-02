<?php

namespace App\Models;

use App\Presenters\UserPresenter;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $full_name
 * @property string $remember_token
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method UserPresenter getPresenter()
 */
class User
	extends Model
	implements Authenticatable {

	use AuthenticableTrait, HasPresenter;

	const
		STATUS_ACTIVE = 'active',
		STATUS_INACTIVE = 'inactive';

	/**
	 * @var array
	 */
	public $fillable = [
		'login',
		'password',
		'full_name',
		'status',
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
	public $hidden = [
		'password',
	];

	/**
	 * @var string
	 */
	public $presenterClass = UserPresenter::class;

	/**
	 * @return array
	 */
	public static function getStatuses(): array {
		return [
			self::STATUS_ACTIVE,
			self::STATUS_INACTIVE,
		];
	}

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
