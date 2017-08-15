<?php

namespace App\ValueObjects\View\Components\Transaction\CList;

use App\ValueObjects\HasInitializationConstructor;

class Options {

	use HasInitializationConstructor;

	/**
	 * @var ?bool
	 */
	protected $highlightFuture;

	/**
	 * @var string[]|null
	 */
	protected $buttons;

	/**
	 * @return bool
	 */
	public function getHighlightFuture(): bool {
		return $this->highlightFuture ?? false;
	}

	/**
	 * @return string[]
	 */
	public function getButtons(): array {
		return $this->buttons ?? [];
	}

}