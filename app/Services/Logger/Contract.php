<?php

namespace App\Services\Logger;

interface Contract {

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function emergency(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function alert(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function critical(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function error(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function warning(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function notice(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function info(...$msg): Contract;

	/**
	 * @param array $msg
	 * @return Contract
	 */
	public function debug(...$msg): Contract;

}