<?php

namespace App\Support\Classes;

use Illuminate\Support\Facades\Log;

class MyLog {

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function emergency($msg) {
		return Log::emergency(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function alert($msg) {
		return Log::alert(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function critical($msg) {
		return Log::critical(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function error($msg) {
		return Log::error(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function warning($msg) {
		return Log::warning(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function notice($msg) {
		return Log::notice(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function info($msg) {
		return Log::info(call_user_func_array('sprintf', func_get_args()));
	}

	/**
	 * @param string $msg
	 * @return bool
	 */
	public function debug($msg) {
		return Log::debug(call_user_func_array('sprintf', func_get_args()));
	}

}