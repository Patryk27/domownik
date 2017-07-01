<?php

namespace App\Filesystem\Memory;

use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;

/**
 * A filesystem entity - represents a file or directory.
 */
class Entity {

	const
		TYPE_FILE = 'file',
		TYPE_DIRECTORY = 'directory';

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * Directory children.
	 * @var Entity[]
	 */
	protected $children;

	/**
	 * File contents.
	 * @var mixed
	 */
	protected $contents;

	/**
	 * @param string $type
	 * @param string $name
	 * @param array $children
	 */
	public function __construct(string $type, string $name) {
		$this->type = $type;
		$this->name = $name;

		$this->children = [];
		$this->contents = null;
	}

	/**
	 * @param string $name
	 * @param mixed $contents
	 * @return Entity
	 */
	public static function buildFile(string $name, $contents = null) {
		$entity = new self(self::TYPE_FILE, $name);
		$entity->setContents($contents);

		return $entity;
	}

	/**
	 * @param string $name
	 * @param Entity[] $children
	 * @return Entity
	 */
	public static function buildDirectory(string $name, array $children = []) {
		$entity = new self(self::TYPE_DIRECTORY, $name);
		$entity->setChildren($children);

		return $entity;
	}

	/**
	 * @param string|array $path
	 * @return Entity|null
	 */
	public function findByPath($path) {
		if (is_string($path)) {
			$pathItems = explode('/', $path);
		} else {
			$pathItems = $path;
		}

		if (empty($pathItems)) {
			return $this;
		}

		if ($pathItems[0] === '.') {
			array_shift($pathItems);

			if (empty($pathItems)) {
				return $this;
			}
		}

		$pathItemCount = count($pathItems);

		$children = $this->children;

		for ($i = 0; $i < $pathItemCount; ++$i) {
			$pathItem = $pathItems[$i];

			if (!array_key_exists($pathItem, $children)) {
				return null;
			}

			if ($i === $pathItemCount - 1) {
				return $children[$pathItem];
			}

			/**
			 * @var Entity $child
			 */
			$child = $children[$pathItem];
			$children = $child->getChildren();
		}

		return null;
	}

	/**
	 * @param string|array $path
	 * @return Entity|null
	 * @throws FileNotFoundException
	 */
	public function findByPathOrFail($path) {
		$entity = $this->findByPath($path);

		if (empty($entity)) {
			throw new FileNotFoundException(Helper::pathToString($path));
		}

		return $entity;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return Entity
	 */
	public function setType(string $type): Entity {
		$this->type = $type;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Entity
	 */
	public function setName(string $name): Entity {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return Entity[]
	 */
	public function getChildren(): array {
		return $this->children;
	}

	/**
	 * @param Entity[] $children
	 * @return Entity
	 */
	public function setChildren(array $children): Entity {
		$this->children = $children;
		return $this;
	}

	/**
	 * @param Entity $child
	 * @return Entity
	 */
	public function addChild(self $child): self {
		$childName = $child->getName();

		if (array_key_exists($childName, $this->children)) {
			// @todo more verbose error message (show full path)
			throw new FileExistsException($child->getName());
		}

		$this->children[$childName] = $child;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getContents() {
		return $this->contents;
	}

	/**
	 * @param mixed $contents
	 * @return Entity
	 */
	public function setContents($contents) {
		$this->contents = $contents;
		return $this;
	}

}