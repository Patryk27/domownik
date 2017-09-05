<?php

namespace App\ValueObjects\Sidebar;

class Item {

	/**
	 * @var Item|null
	 */
	protected $parent;

	/**
	 * @var string|null
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $caption;

	/**
	 * @var string|null
	 */
	protected $icon;

	/**
	 * @var string|null
	 */
	protected $badge;

	/**
	 * @var bool|null
	 */
	protected $isTemplate;

	/**
	 * @var Item[]
	 */
	protected $children = [];

	/**
	 * @return Item|null
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param Item|null $parent
	 * @return $this
	 */
	public function setParent($parent): self {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFullName(): string {
		$result = '';

		if ($this->hasParent() && $this->parent->hasName()) {
			$result .= $this->parent->getFullName() . '.';
		}

		$result .= $this->name;

		return $result;
	}

	/**
	 * @return bool
	 */
	public function hasParent(): bool {
		return !is_null($this->parent);
	}

	/**
	 * @return bool
	 */
	public function hasName(): bool {
		return !empty($this->name);
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
	 * @param string $url
	 * @return $this
	 */
	public function setUrl(string $url): self {
		$this->url = $url;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasCaption(): bool {
		return !empty($this->caption);
	}

	/**
	 * @return string
	 */
	public function getCaption(): string {
		return $this->caption;
	}

	/**
	 * @param string $caption
	 * @return $this
	 */
	public function setCaption(string $caption): self {
		$this->caption = $caption;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasIcon(): bool {
		return !empty($this->icon);
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return $this->icon;
	}

	/**
	 * @param string $icon
	 * @return $this
	 */
	public function setIcon(string $icon): self {
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasBadge(): bool {
		return !empty($this->badge);
	}

	/**
	 * @return string
	 */
	public function getBadge(): string {
		return $this->badge;
	}

	/**
	 * @param string $badge
	 * @return $this
	 */
	public function setBadge(string $badge): self {
		$this->badge = $badge;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasSubitems(): bool {
		return !empty($this->children);
	}

	/**
	 * @param Item $item
	 * @return $this
	 */
	public function addChild(Item $item): self {
		$this->children[$item->getName()] = $item;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName(string $name): self {
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $itemName
	 * @return Item|null
	 */
	public function findSubitemByName(string $itemName) {
		$splitItemNames = explode('.', $itemName);

		$currentItem = $this;

		while (!empty($splitItemNames)) {
			$itemName = $splitItemNames[0];

			$currentSubitems = $currentItem->getChildren();

			if (isset($currentSubitems[$itemName])) {
				array_shift($splitItemNames);

				if (empty($splitItemNames)) {
					return $currentSubitems[$itemName];
				}

				$currentItem = $currentSubitems[$itemName];
			} else {
				return null;
			}
		}

		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function getChildren(): array {
		return $this->children;
	}

	/**
	 * @param Item[] $children
	 * @return $this
	 */
	public function setChildren(array $children): self {
		$this->children = $children;
		return $this;
	}

	/**
	 * @return Item[]
	 */
	public function getVisibleSubitems(): array {
		return array_filter($this->children, function ($subitem) {
			/**
			 * @var Item $subitem
			 */

			return $subitem->isVisible();
		});
	}

	/**
	 * @return bool
	 */
	public function isVisible() {
		return !$this->isTemplate();
	}

	/**
	 * @return bool
	 */
	public function isTemplate(): bool {
		return (bool)$this->isTemplate;
	}

	/**
	 * @return Item
	 */
	public function getClone(): Item {
		$subitems = [];

		foreach ($this->children as $subitem) {
			$subitems[] = $subitem->getClone();
		}

		$item = new Item();
		$item
			->setParent($this->parent)
			->setName($this->name)
			->setUrl($this->url)
			->setCaption($this->caption)
			->setIcon($this->icon)
			->setBadge($this->badge)
			->setIsTemplate(false)
			->setChildren($subitems);

		return $item;
	}

	/**
	 * @param bool $isTemplate
	 * @return $this
	 */
	public function setIsTemplate(bool $isTemplate): self {
		$this->isTemplate = $isTemplate;
		return $this;
	}

}