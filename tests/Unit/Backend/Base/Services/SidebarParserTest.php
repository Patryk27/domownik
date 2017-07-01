<?php

namespace Tests\Unit\Backend\Services;

use App\Filesystem\Memory as MemoryFilesystem;
use App\Services\Sidebar\Parser as SidebarParser;
use App\ValueObjects\Sidebar\Item as SidebarItem;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactoryContract;
use Illuminate\Filesystem\FilesystemManager;
use Tests\TestCase;

class SidebarParserTest
	extends TestCase {

	/**
	 * @var FilesystemManager
	 */
	protected $storage;

	/**
	 * @var SidebarParser
	 */
	protected $sidebarParser;

	/**
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();

		$vfs = new MemoryFilesystem();

		/**
		 * Create file 'parse-valid.xml'
		 */
		$vfs->put('parse-valid.xml', <<<END
<sidebar module-name="Test">
	<item name="first" icon="first-icon">
		<item name="first-first" url="first-first-url"/>
		
		<item-template name="first-second" icon="first-second-icon"/>
	</item>
	
	<item name="second" icon="second-icon" url="second-url"/>
</sidebar>
END
		);

		/**
		 * Create file 'parse-error.xml'
		 */
		$vfs->put('parse-error.xml', <<<END
<sidebar module-name="Test">
	<error
</sidebar>
END
		);

		$this->storage = new FilesystemManager($this->app);
		$this->storage->set('app', $vfs);

		$this->app
			->when(SidebarParser::class)
			->needs(FilesystemFactoryContract::class)
			->give(function() {
				return $this->storage;
			});

		/**
		 * @var SidebarParser $sidebarParser
		 */
		$this->sidebarParser = $this->app->make(SidebarParser::class);
	}

	/**
	 * Tests if parseFile() works properly on a specially prepared file.
	 */
	public function testParseValid() {
		$this->sidebarParser->parseFile('parse-valid.xml');

		// check module name
		$this->assertEquals('Test', $this->sidebarParser->getModuleName());

		// check root item
		$rootItem = $this->sidebarParser->getRootItem();
		$this->assertInstanceOf(SidebarItem::class, $rootItem);

		// check first children
		$items = $rootItem->getChildren();

		$this->assertCount(2, $items);
		$this->assertArrayHasKey('first', $items);
		$this->assertArrayHasKey('second', $items);

		/**
		 * @var SidebarItem $item
		 */

		// check the 'first' item
		$item = $items['first'];
		$itemChildren = $item->getChildren();

		$this->assertEquals('first', $item->getName());
		$this->assertEquals('first-icon', $item->getIcon());

		$this->assertCount(2, $itemChildren);
		$this->assertArrayHasKey('first-first', $itemChildren);
		$this->assertArrayHasKey('first-second', $itemChildren);

		// check the 'second' item
		$item = $items['second'];
		$itemChildren = $item->getChildren();

		$this->assertEquals('second', $item->getName());
		$this->assertEquals('second-icon', $item->getIcon());
		$this->assertEquals('second-url', $item->getUrl());

		$this->assertCount(0, $itemChildren);
	}

	/**
	 * Tests if parseFile() fails on an invalid XML file.
	 */
	public function testParseError() {
		$this->expectExceptionMessage('Unable to parse XML from string.');
		$this->sidebarParser->parseFile('parse-error.xml');
	}

}