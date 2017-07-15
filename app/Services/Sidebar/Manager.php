<?php

namespace App\Services\Sidebar;

use App\Exceptions\InternalException;
use App\Services\Section\ManagerContract as SectionManagerContract;
use App\ValueObjects\Sidebar;

class Manager
	implements ManagerContract {

	/**
	 * @var SectionManagerContract
	 */
	protected $sectionManager;

	/**
	 * @var ParserContract
	 */
	protected $sidebarParser;

	/**
	 * @var Sidebar[]
	 */
	protected $sidebars = [];

	/**
	 * @param SectionManagerContract $sectionManager
	 */
	public function __construct(
		SectionManagerContract $sectionManager,
		ParserContract $sidebarParser
	) {
		$this->sectionManager = $sectionManager;
		$this->sidebarParser = $sidebarParser;

		$this->loadSidebars();
	}

	/**
	 * @inheritDoc
	 */
	public function getSidebars(): array {
		return $this->sidebars;
	}

	/**
	 * Loads all the sidebars.
	 * @return Manager
	 */
	protected function loadSidebars(): Manager {
		$sectionNames = $this->sectionManager->getSectionNames();

		foreach ($sectionNames as $sectionName) {
			$fileName = sprintf('sidebars/%s.xml', $sectionName);

			$this->loadSidebar($sectionName, $fileName);
		}

		return $this;
	}

	/**
	 * Loads given sidebar file and adds it into the sidebar list.
	 * @param string $sectionName
	 * @param string $fileName
	 * @return Manager
	 */
	protected function loadSidebar(string $sectionName, string $fileName): Manager {
		$sidebar = $this->sidebarParser->parseXml($fileName);

		if ($sidebar->getSectionName() !== $sectionName) {
			throw new InternalException('Sidebar loaded from file \'%s\' has invalid section name (expected: \'%s\').', $fileName, $sectionName);
		}

		$this->sidebars[$sectionName] = $sidebar;

		return $this;
	}

}