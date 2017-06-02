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
	protected $subitems = [];

	/**
	 * @return bool
	 */
	public function hasParent() {
		return !is_null($this->parent);
	}

	/**
	 * @return Item
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param Item $parent
	 * @return $this
	 */
	public function setParent($parent) {
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasName() {
		return !empty($this->name);
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFullName() {
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
	public function hasUrl() {
		return !empty($this->url);
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url) {
		$this->url = $url;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasCaption() {
		return !empty($this->caption);
	}

	/**
	 * @return string
	 */
	public function getCaption() {
		return $this->caption;
	}

	/**
	 * @param string $caption
	 * @return $this
	 */
	public function setCaption($caption) {
		$this->caption = $caption;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasIcon() {
		return !empty($this->icon);
	}

	/**
	 * @return string
	 */
	public function getIcon() {
		return $this->icon;
	}

	/**
	 * @param string $icon
	 * @return $this
	 */
	public function setIcon($icon) {
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasBadge() {
		return !empty($this->badge);
	}

	/**
	 * @return string
	 */
	public function getBadge() {
		return $this->badge;
	}

	/**
	 * @param string $badge
	 * @return $this
	 */
	public function setBadge($badge) {
		$this->badge = $badge;
		return $this;
	}

	/**
	 * @param bool $isTemplate
	 * @return $this
	 */
	public function setIsTemplate($isTemplate) {
		$this->isTemplate = $isTemplate;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isTemplate() {
		return (bool)$this->isTemplate;
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
	public function hasSubitems() {
		return !empty($this->subitems);
	}

	/**
	 * @inheritDoc
	 */
	public function getSubitems() {
		return $this->subitems;
	}

	/**
	 * @param Item[] $subitems
	 * @return $this
	 */
	public function setSubitems($subitems) {
		$this->subitems = $subitems;
		return $this;
	}

	/**
	 * @param Item $item
	 * @return $this
	 */
	public function addSubitem($item) {
		$this->subitems[$item->getName()] = $item;
		return $this;
	}

	/**
	 * @param string $itemName
	 * @return Item|null
	 */
	public function findSubitemByName($itemName) {
		$splitItemNames = explode('.', $itemName);

		$currentItem = $this;

		while (!empty($splitItemNames)) {
			$itemName = $splitItemNames[0];

			$currentSubitems = $currentItem->getSubitems();

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
	 * @param Item []
	 * @return Item[]|array
	 */
	public function getVisibleSubitems() {
		return array_filter($this->subitems, function($subitem) {
			/**
			 * @var Item $subitem
			 */

			return $subitem->isVisible();
		});
	}

	/**
	 * @return Item
	 */
	public function getClone() {
		// clone subitems
		$subitems = [];

		foreach ($this->subitems as $subitem) {
			$subitems[] = $subitem->getClone();
		}

		// prepare the model
		$item = new Item();
		$item
			->setParent($this->parent)
			->setName($this->name)
			->setUrl($this->url)
			->setCaption($this->caption)
			->setIcon($this->icon)
			->setBadge($this->badge)
			->setIsTemplate(false)
			->setSubitems($subitems);

		return $item;
	}

}