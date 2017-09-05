<?php

namespace App\ValueObjects\View\Components\Transaction\CList;

use App\ValueObjects\HasInitializationConstructor;
use App\ValueObjects\HasOverwrite;

class Options {

	use HasInitializationConstructor, HasOverwrite;

	/**
	 * @var ?bool
	 */
	protected $highlightFuture;

	/**
	 * @var string[]|null
	 */
	protected $buttons;

	/**
	 * @var ?bool
	 */
	protected $showCounter;

	/**
	 * @var ?bool
	 */
	protected $showRowCounter;

	/**
	 * @return bool
	 */
	public function getHighlightFuture(): bool {
		return $this->highlightFuture ?? false;
	}

	/**
	 * @return bool
	 */
	public function hasButtons(): bool {
		return !empty($this->getButtons());
	}

	/**
	 * @return string[]
	 */
	public function getButtons(): array {
		return $this->buttons ?? [];
	}

	/**
	 * @param string $buttonName
	 * @return bool
	 */
	public function hasButton(string $buttonName): bool {
		return in_array($buttonName, $this->getButtons());
	}

	/**
	 * @return bool
	 */
	public function getShowCounter(): bool {
		return $this->showCounter ?? false;
	}

	/**
	 * @return bool
	 */
	public function getShowRowCounter(): bool {
		return $this->showRowCounter ?? false;
	}

}