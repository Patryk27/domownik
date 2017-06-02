<?php

namespace App\ValueObjects;

class Breadcrumb {

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * Breadcrumb constructor.
	 * @param string $url
	 * @param string $name
	 */
	public function __construct($url, $name) {
		$this->url = $url;
		$this->name = $name;
	}

	/**
	 * @return bool
	 */
	public function hasUrl(): bool {
		return !empty($this->url);
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

}