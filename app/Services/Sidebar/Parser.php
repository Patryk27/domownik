<?php

namespace App\Services\Sidebar;

use App\Exceptions\Exception;
use App\ValueObjects\Sidebar;
use App\ValueObjects\Sidebar\Item as SidebarItem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactoryContract;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Laravie\Parser\Document as XmlDocument;
use SimpleXMLElement;
use XmlParser;

class Parser
	implements ParserContract {

	/**
	 * @var FilesystemContract
	 */
	protected $fs;

	/**
	 * @var string
	 */
	protected $sectionName;

	/**
	 * @var SidebarItem
	 */
	protected $rootItem;

	/**
	 * @param FilesystemFactoryContract $fsFactory
	 */
	public function __construct(
		FilesystemFactoryContract $fsFactory
	) {
		$this->fs = $fsFactory->disk('resources');
	}

	/**
	 * @inheritdoc
	 */
	public function parseXml(string $fileName): Sidebar {
		// @todo cache

		if (!$this->fs->exists($fileName)) {
			throw new Exception('Could not find sidebar file: %s.', $fileName);
		}

		$fileContents = $this->fs->get($fileName);
		$xmlDocument = XmlParser::extract($fileContents);

		$this->parseDocument($xmlDocument);

		return new Sidebar($this->sectionName, $this->rootItem);
	}

	/**
	 * @return $this
	 */
	protected function parseDocument(XmlDocument $xml): Parser {
		/**
		 * @var SimpleXMLElement $xmlRootNode
		 */
		$xmlRootNode = $xml->getContent();
		$xmlRootNodeAttributes = $xmlRootNode->attributes();

		// parse section name
		$this->sectionName = (string)$xmlRootNodeAttributes['section-name'];

		if (empty($this->sectionName)) {
			throw new Exception('No module name specified in root node (no \'section-name\' attribute found).');
		}

		// parse items
		$this->rootItem = new SidebarItem();

		foreach ($xmlRootNode->children() as $xmlNode) {
			$this->rootItem->addChild($this->processNode($this->rootItem, $xmlNode));
		}

		return $this;
	}

	/**
	 * @param SidebarItem $parentItem
	 * @param SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function processNode(SidebarItem $parentItem, SimpleXMLElement $xmlNode): SidebarItem {
		switch ($xmlNode->getName()) {
			case 'item':
			case 'item-template':
				return $this->processItem($parentItem, $xmlNode);

			default:
				// @todo do not throw generic exception
				throw new Exception('Unexpected node: %s.', $xmlNode->getName());
		}
	}

	/**
	 * @param SidebarItem $parentItem
	 * @param SimpleXMLElement $xmlNode
	 * @return SidebarItem
	 */
	protected function processItem(SidebarItem $parentItem, SimpleXMLElement $xmlNode): SidebarItem {
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
			$itemTranslationIdentifier = sprintf('sidebars/%s.%s', $this->sectionName, $sidebarItem->getFullName());
			$sidebarItem->setCaption(__($itemTranslationIdentifier));
		}

		foreach ($xmlNode->children() as $xmlChildNode) {
			$sidebarItem->addChild($this->processNode($sidebarItem, $xmlChildNode));
		}

		return $sidebarItem;
	}

}