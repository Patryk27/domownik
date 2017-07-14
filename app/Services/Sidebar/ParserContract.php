<?php

namespace App\Services\Sidebar;

use App\ValueObjects\Sidebar;

interface ParserContract {

	/**
	 * Parses given sidebar XML file and returns sidebar.
	 * @param string $fileName
	 * @return Sidebar
	 */
	public function parseXml(string $fileName): Sidebar;

}