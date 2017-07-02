<?php

namespace App\Services\Sidebar;

use App\Exceptions\Exception;
use App\ValueObjects\Sidebar\Item as SidebarItem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactoryContract;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Laravie\Parser\Document as XmlDocument;
use SimpleXMLElement;
use XmlParser;

class Parser {

	/**
	 * @var FilesystemContract
	 */
	protected $fs;

	/**
	 * @var XmlDocument
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
	protected $rootItem;

	/**
	 * @param FilesystemFactoryContract $fsFactory
	 */
	public function __construct(
		FilesystemFactoryContract $fsFactory
	) {
		$this->fs = $fsFactory->disk('app');
	}

	/**
	 * @param string $fileName
	 * @return $this
	 */
	public function parseFile(string $fileName): self {
		// @todo cache

		if (!$this->fs->exists($fileName)) {
			throw new Exception('Could not find sidebar file: %s.', $fileName);
		}

		$fileContents = $this->fs->get($fileName);
		$this->xml = XmlParser::extract($fileContents);

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
	public function getRootItem(): SidebarItem {
		return $this->rootItem;
	}

	/**
	 * @return $this
	 */
	protected function parseXml(): Parser {
		/**
		 * @var SimpleXMLElement $xmlRootNode
		 */
		$xmlRootNode = $this->xml->getContent();
		$xmlRootNodeAttributes = $xmlRootNode->attributes();

		$this->moduleName = (string)$xmlRootNodeAttributes['module-name'];

		if (empty($this->moduleName)) {
			throw new Exception('No module name specified in root node (no \'module-name\' attribute found).');
		}

		$this->rootItem = new SidebarItem();

		foreach ($xmlRootNode->children() as $xmlNode) {
			$this->rootItem->addChild($this->parseNode($this->rootItem, $xmlNode));
		}

		return $this;
	}

	/**
	 * @param SidebarItem $parentItem
	 * @param SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function parseNode(SidebarItem $parentItem, SimpleXMLElement $xmlNode): SidebarItem {
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
	 * @param SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function parseItem(SidebarItem $parentItem, SimpleXMLElement $xmlNode): SidebarItem {
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
			$sidebarItem->addChild($this->parseNode($sidebarItem, $xmlChildNode));
		}

		return $sidebarItem;
	}

}