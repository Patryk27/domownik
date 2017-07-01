<?php

namespace App\Filesystem;

use App\Filesystem\Memory\Entity as MemoryFsEntity;
use App\Filesystem\Memory\Helper as MemoryFsHelper;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use League\Flysystem\Exception as FsException;
use League\Flysystem\NotSupportedException;

/**
 * Creates a virtual file system mapped in a memory.
 */
class Memory
	implements FilesystemContract {

	/**
	 * @var MemoryFsEntity
	 */
	protected $root;

	/**
	 * Memory constructor.
	 */
	public function __construct() {
		$this->root = new MemoryFsEntity(MemoryFsEntity::TYPE_DIRECTORY, '.');
	}

	/**
	 * @inheritDoc
	 */
	public function exists($path) {
		return !empty($this->root->findByPath($path));
	}

	/**
	 * @inheritDoc
	 */
	public function get($path) {
		$entity = $this->root->findByPathOrFail($path);

		if ($entity->getType() === MemoryFsEntity::TYPE_DIRECTORY) {
			throw new NotSupportedException('Tried to call get() on a directory.');
		}

		return $entity->getContents();
	}

	/**
	 * @inheritDoc
	 */
	public function put($path, $contents, $visibility = null) {
		if (empty($path)) {
			throw new FsException('Cannot create a file with no name.');
		}

		$path = MemoryFsHelper::parsePath($path);
		$fileName = array_pop($path);

		$this->root
			->findByPathOrFail($path)
			->addChild(MemoryFsEntity::buildFile($fileName, $contents));
	}

	/**
	 * @inheritDoc
	 */
	public function getVisibility($path) {
		throw new \App\Exceptions\UnimplementedException('getVisibility() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function setVisibility($path, $visibility) {
		throw new \App\Exceptions\UnimplementedException('setVisibility() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function prepend($path, $data) {
		throw new \App\Exceptions\UnimplementedException('prepend() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function append($path, $data) {
		throw new \App\Exceptions\UnimplementedException('append() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function delete($paths) {
		throw new \App\Exceptions\UnimplementedException('delete() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function copy($from, $to) {
		throw new \App\Exceptions\UnimplementedException('copy() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function move($from, $to) {
		throw new \App\Exceptions\UnimplementedException('move() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function size($path) {
		throw new \App\Exceptions\UnimplementedException('size() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function lastModified($path) {
		throw new \App\Exceptions\UnimplementedException('lastModified() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function files($directory = null, $recursive = false) {
		throw new \App\Exceptions\UnimplementedException('files() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function allFiles($directory = null) {
		throw new \App\Exceptions\UnimplementedException('allFiles() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function directories($directory = null, $recursive = false) {
		throw new \App\Exceptions\UnimplementedException('directories() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function allDirectories($directory = null) {
		throw new \App\Exceptions\UnimplementedException('allDirectories() unimplemented.');
	}

	/**
	 * @inheritDoc
	 */
	public function makeDirectory($path) {
		if (empty($path)) {
			throw new FsException('Cannot create a directory with no name.');
		}

		$path = MemoryFsHelper::parsePath($path);
		$directoryName = array_pop($path);

		$this->root
			->findByPathOrFail($path)
			->addChild(MemoryFsEntity::buildDirectory($directoryName));
	}

	/**
	 * @inheritDoc
	 */
	public function deleteDirectory($directory) {
		throw new \App\Exceptions\UnimplementedException('deleteDirectory() unimplemented.');
	}

}