<?php

namespace App\Services\Breadcrumb;

use App\Exceptions\InternalException;
use App\ValueObjects\Breadcrumb;

class Manager
	implements \Countable {

	/**
	 * @var Breadcrumb[]
	 */
	protected $breadcrumbs;

	/**
	 * @var CustomPushHandlerContract[]
	 */
	protected $customPushHandlers;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->breadcrumbs = [];
		$this->customPushHandlers = [];
	}

	/**
	 * @param string $url
	 * @param string $title
	 * @return $this
	 */
	public function push(string $url, string $title): self {
		$this->breadcrumbs[] = new Breadcrumb($url, $title);
		return $this;
	}

	/**
	 * @param mixed $custom
	 * @return $this
	 * @throws InternalException
	 */
	public function pushCustom($custom): self {
		foreach ($this->customPushHandlers as $customPushHandler) {
			$result = $customPushHandler->getBreadcrumb($custom);

			if (!empty($result)) {
				$this->breadcrumbs[] = $result;
				return $this;
			}
		}

		throw new InternalException('No valid custom push handler found.');
	}

	/**
	 * @param CustomPushHandlerContract $customPushHandler
	 * @return $this
	 */
	public function registerCustomPushHandler(CustomPushHandlerContract $customPushHandler): self {
		$this->customPushHandlers[] = $customPushHandler;
		return $this;
	}

	/**
	 * @return Breadcrumb[]
	 */
	public function get() {
		return $this->breadcrumbs;
	}

	/**
	 * @inheritDoc
	 */
	public function count() {
		return count($this->breadcrumbs);
	}

}