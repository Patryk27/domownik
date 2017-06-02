<?php

namespace App\Services\Sidebar;

use App\Exceptions\Exception;
use App\ValueObjects\Sidebar\Item as SidebarItem;
use Illuminate\Filesystem\Filesystem;

class Parser {

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var \Laravie\Parser\Document
	 */
	protected $xml;

	/**
	 * Module name read from the XML file.
	 * Used to perform text translations.
	 * @var string
	 */
	protected $moduleName;

	/**
	 * First, collective sidebar item.
	 * All the 'real' sidebar items are its children (subitems).
	 * @var SidebarItem
	 */
	protected $mainItem;

	/**
	 * Parser constructor.
	 * @param Filesystem $filesystem
	 */
	public function __construct(
		Filesystem $filesystem
	) {
		$this->filesystem = $filesystem;
	}

	/**
	 * @param string $fileName
	 * @return $this
	 */
	public function parseFile($fileName): Parser {
		if (!$this->filesystem->exists($fileName)) {
			throw new Exception('Could not find sidebar file: %s.', $fileName);
		}

		$fileContents = $this->filesystem->get($fileName);
		$this->xml = \XmlParser::extract($fileContents);

		$this->parseXml();

		return $this;
	}

	/**
	 * @return string
	 */
	public function getModuleName(): string {
		return $this->moduleName;
	}

	/**
	 * @return SidebarItem
	 */
	public function getMainItem(): SidebarItem {
		return $this->mainItem;
	}

	/**
	 * @return $this
	 */
	protected function parseXml(): Parser {
		/**
		 * @var \SimpleXMLElement $xmlRootNode
		 */
		$xmlRootNode = $this->xml->getContent();
		$xmlRootNodeAttributes = $xmlRootNode->attributes();

		$this->moduleName = (string)$xmlRootNodeAttributes['module-name'];

		if (empty($this->moduleName)) {
			throw new Exception('No module name specified in root node (no \'module-name\' attribute found).');
		}

		$this->mainItem = new SidebarItem();

		foreach ($xmlRootNode->children() as $xmlNode) {
			$this->mainItem->addSubitem($this->parseNode($this->mainItem, $xmlNode));
		}

		return $this;
	}

	/**
	 * @param SidebarItem $parentItem
	 * @param \SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function parseNode($parentItem, $xmlNode): SidebarItem {
		switch ($xmlNode->getName()) {
			case 'item':
			case 'item-template':
				return $this->parseItem($parentItem, $xmlNode);
				break;

			default:
				throw new Exception('Unexpected node: %s.', $xmlNode->getName());
		}
	}
	/**
	 * @param SidebarItem $parentItem
	 * @param \SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function parseItem($parentItem, $xmlNode): SidebarItem {
		$nodeAttributes = $xmlNode->attributes();

		$sidebarItem = new SidebarItem();
		$sidebarItem
			->setParent($parentItem)
			->setName((string)$nodeAttributes['name'])
			->setUrl((string)$nodeAttributes['url'])
			->setCaption((string)$nodeAttributes['caption'])
			->setIcon((string)$nodeAttributes['icon'])
			->setBadge((string)$nodeAttributes['badge'])
			->setIsTemplate($xmlNode->getName() === 'item-template');

		if (!$sidebarItem->hasCaption()) {
			$itemTranslationIdentifier = sprintf('%s::sidebar.%s', $this->moduleName, $sidebarItem->getFullName());
			$sidebarItem->setCaption(__($itemTranslationIdentifier));
		}

		foreach ($xmlNode->children() as $xmlChildNode) {
			$sidebarItem->addSubitem($this->parseNode($sidebarItem, $xmlChildNode));
		}

		return $sidebarItem;
	}

}