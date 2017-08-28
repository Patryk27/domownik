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
	protected $caption;

	/**
	 * @param string $url
	 * @param string $caption
	 */
	public function __construct(
		string $url,
		string $caption
	) {
		$this->url = $url;
		$this->caption = $caption;
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
	public function getCaption(): string {
		return $this->caption;
	}

}