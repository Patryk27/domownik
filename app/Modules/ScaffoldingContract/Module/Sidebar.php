<?php

namespace App\Modules\ScaffoldingContract\Module;

use App\Exceptions\Exception;
use App\ValueObjects\Sidebar\Item as SidebarItem;

interface Sidebar {

	/**
	 * Returns all sidebar items.
	 * @return SidebarItem[]
	 */
	public function getItems(): array;

	/**
	 * Returns item by its name; supports dot syntax.
	 * (that is: 'foo.bar' with return Foo's child named 'Bar').
	 * Throws an exception if no item is found.
	 * @param string $itemName
	 * @return SidebarItem
	 * @throws Exception
	 */
	public function getItemByName(string $itemName): SidebarItem;

}