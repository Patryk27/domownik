<?php

namespace App\Modules\Scaffolding\Module;

use App\Exceptions\Exception;
use App\Services\Sidebar\Parser as SidebarParser;
use App\ValueObjects\Sidebar\Item as SidebarItem;

class Sidebar
	implements \App\Modules\ScaffoldingContract\Module\Sidebar {

	/**
	 * @var SidebarParser
	 */
	protected $sidebarParser;

	/**
	 * @var SidebarItem
	 */
	protected $mainItem;

	/**
	 * Sidebar constructor.
	 * @param SidebarParser $sidebarParser
	 */
	public function __construct(
		SidebarParser $sidebarParser
	) {
		$this->sidebarParser = $sidebarParser;
	}

	/**
	 * @inheritdoc
	 */
	public function getItems(): array {
		return $this->mainItem->getSubitems();
	}

	/**
	 * @inheritdoc
	 */
	public function getItemByName(string $itemName): SidebarItem {
		$item = $this->mainItem->findSubitemByName($itemName);

		if (is_null($item)) {
			throw new Exception('Sidebar item not found: %s.', $itemName);
		}

		return $item;
	}

	/**
	 * @param string $sidebarFileName
	 * @return $this
	 */
	public function loadFromFile($sidebarFileName) {
		$this->sidebarParser->parseFile($sidebarFileName);
		$this->mainItem = $this->sidebarParser->getMainItem();

		return $this;
	}

}