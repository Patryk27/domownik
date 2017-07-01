<?php

namespace Tests\Unit\Backend\Filesystem;

use App\Filesystem\Memory as MemoryFilesystem;
use Tests\TestCase;

class MemoryFilesystemTest
	extends TestCase {

	/**
	 * @var MemoryFilesystem
	 */
	protected $memoryFs;

	/**
	 * Prepares the test.
	 */
	public function setUp() {
		parent::setUp();

		$this->memoryFs = new MemoryFilesystem();

		$this->memoryFs->makeDirectory('a');
		$this->memoryFs->makeDirectory('b');
		$this->memoryFs->makeDirectory('b/c');

		$this->memoryFs->put('a/1.txt', 'first');
		$this->memoryFs->put('b/c/2.txt', 'second');
	}

	/**
	 * Checks if exists() works properly.
	 */
	public function testExists() {
		$this->assertTrue($this->memoryFs->exists('a'));
		$this->assertTrue($this->memoryFs->exists('b'));
		$this->assertTrue($this->memoryFs->exists('b/c'));
		$this->assertTrue($this->memoryFs->exists('a/1.txt'));
		$this->assertTrue($this->memoryFs->exists('b/c/2.txt'));

		$this->assertFalse($this->memoryFs->exists('c'));
		$this->assertFalse($this->memoryFs->exists('1.txt'));
		$this->assertFalse($this->memoryFs->exists('2.txt'));
	}

	/**
	 * Checks if get() works properly.
	 */
	public function testGet() {
		$this->assertEquals('first', $this->memoryFs->get('a/1.txt'));
		$this->assertEquals('second', $this->memoryFs->get('b/c/2.txt'));
	}

	/**
	 * Checks if put() throws an exception when trying to create an already existing file.
	 */
	public function testPutOnExistingFile() {
		$this->expectExceptionMessage('File already exists at path: a');
		$this->memoryFs->put('a', 'test');
	}

	/**
	 * Checks if put() throws an exception when trying to create a file in a non-existing directory.
	 */
	public function testPutOnNonExistingDirectory() {
		$this->expectExceptionMessage('File not found at path: a/b/c');
		$this->memoryFs->put('a/b/c/d', 'test');
	}

	/**
	 * Checks if makeDirectory() throws an exception when trying to create a directory in a non-existing parent
	 * directory.
	 */
	public function testMakeDirectoryOnNonExistingDirectory() {
		$this->expectExceptionMessage('File not found at path: a/b/c');
		$this->memoryFs->makeDirectory('a/b/c/d');
	}

	/**
	 * Checks if get() throws an exception when trying to read an non-existing file.
	 */
	public function testGetOnNonExistingFile() {
		$this->expectExceptionMessage('File not found at path: xyz');
		$this->memoryFs->get('xyz');
	}

	/**
	 * Checks if get() throws an exception when trying to get contents of a directory.
	 */
	public function testGetOnDirectory() {
		$this->expectExceptionMessage('Tried to call get() on a directory.');
		$this->memoryFs->get('a');
	}

	/**
	 * Checks if makeDirectory() throws an exception when trying to create a directory with no name.
	 */
	public function testMakeDirectoryEmptyName() {
		$this->expectExceptionMessage('Cannot create a directory with no name.');
		$this->memoryFs->makeDirectory('');
	}

	/**
	 * Checks if put() throws an exception when trying to create a file with no name.
	 */
	public function testPutEmptyName() {
		$this->expectExceptionMessage('Cannot create a file with no name.');
		$this->memoryFs->put('', null);
	}

}
