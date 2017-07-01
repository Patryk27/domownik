<?php

namespace App\Services\Logger;

use Illuminate\Log\Writer as LogWriter;

class Standard
	implements Contract {

	/**
	 * @var LogWriter
	 */
	protected $log;

	/**
	 * @param LogWriter $log
	 */
	public function __construct(
		LogWriter $log
	) {
		$this->log = $log;
	}

	/**
	 * @inheritDoc
	 */
	public function emergency(...$msg): Contract {
		$this->log->emergency(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function alert(...$msg): Contract {
		$this->log->alert(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function critical(...$msg): Contract {
		$this->log->critical(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function error(...$msg): Contract {
		$this->log->error(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function warning(...$msg): Contract {
		$this->log->warning(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function notice(...$msg): Contract {
		$this->log->notice(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function info(...$msg): Contract {
		$this->log->info(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function debug(...$msg): Contract {
		$this->log->debug(call_user_func_array('sprintf', func_get_args()));
		return $this;
	}

}