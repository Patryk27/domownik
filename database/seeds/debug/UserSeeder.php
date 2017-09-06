<?php

namespace Database\Seeds\Debug;

use App\Models\User;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Seeder;

class UserSeeder
	extends Seeder {

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var CacheRepository
	 */
	protected $cache;

	/**
	 * @param DatabaseConnection $db
	 * @param CacheRepository $cache
	 */
	public function __construct(
		DatabaseConnection $db,
		CacheRepository $cache
	) {
		$this->db = $db;
		$this->cache = $cache;
	}

	/**
	 * @return void
	 */
	public function run() {
		$this->db->table('users')->delete();

		(new User([
			'login' => 'admin',
			'password' => bcrypt('admin'),
			'full_name' => 'Admin Admin',
			'status' => User::STATUS_ACTIVE,
		]))->save();

		$this->cache->flush();
	}

}