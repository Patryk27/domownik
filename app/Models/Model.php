<?php

namespace App\Models;

use Illuminate\Cache\TaggedCache;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Cache;

abstract class Model
	extends BaseModel {

	/**
	 * @return string
	 */
	abstract public static function getCacheConfiguration(): array;

	/**
	 * @return TaggedCache
	 */
	public static function getCache() {
		$cacheConfiguration = static::getCacheConfiguration();
		return Cache::tags($cacheConfiguration['tags']);
	}

	/**
	 * @return TaggedCache
	 */
	public static function getFlushCache() {
		$cacheConfiguration = static::getCacheConfiguration();
		return Cache::tags($cacheConfiguration['flush-tags']);
	}

}