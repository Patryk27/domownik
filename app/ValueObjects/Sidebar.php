<?php

namespace App\ValueObjects;

use App\ValueObjects\Sidebar\Item as SidebarItem;

class Sidebar {

	/**
	 * @var string
	 */
	protected $sectionName;

	/**
	 * @var SidebarItem
	 */
	protected $rootItem;

	/**
	 * @param string $sectionName
	 * @param SidebarItem $rootItem
	 */
	public function __construct(
		string $sectionName,
		SidebarItem $rootItem
	) {
		$this->sectionName = $sectionName;
		$this->rootItem = $rootItem;
	}

	/**
	 * @return string
	 */
	public function getSectionName(): string {
		return $this->sectionName;
	}

	/**
	 * @return SidebarItem
	 */
	public function getRootItem(): SidebarItem {
		return $this->rootItem;
	}

	/**
	 * @return SidebarItem[]
	 */
	public function getItems(): array {
		return $this->rootItem->getChildren();
	}

}