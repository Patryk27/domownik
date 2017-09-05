<?php

namespace App\Services\Breadcrumb;

use App\Exceptions\InternalException;
use App\ValueObjects\Breadcrumb;
use Illuminate\Support\Collection;

class Manager
	implements ManagerContract {

	/**
	 * @var Collection|Breadcrumb[]
	 */
	protected $breadcrumbs;

	/**
	 * @var Collection|PushHandlerContract[]
	 */
	protected $pushHandlers;

	public function __construct() {
		$this->breadcrumbs = new Collection();
		$this->pushHandlers = new Collection();

		$this->pushHandlers->push(new class
			implements PushHandlerContract {

			/**
			 * @inheritDoc
			 */
			public function handle($value): ?Breadcrumb {
				if (is_object($value) && $value instanceof Breadcrumb) {
					return $value;
				}

				return null;
			}

		});
	}

	/**
	 * @inheritDoc
	 */
	public function push($value) {
		foreach ($this->pushHandlers as $customPushHandler) {
			$breadcrumb = $customPushHandler->handle($value);

			if (isset($breadcrumb)) {
				if (is_object($breadcrumb) && $breadcrumb instanceof Breadcrumb) {
					$this->breadcrumbs[] = $breadcrumb;
					return $this;
				}

				throw new InternalException('Custom push handler did not return a valid breadcrumb.');
			}
		}

		if (is_object($value)) {
			throw new InternalException('No valid custom push handler found for an instance of class [%s].', get_class($value));
		} else {
			throw new InternalException('No valid custom push handler found for type [%s].', gettype($value));
		}
	}

	/**
	 * @inheritDoc
	 */
	public function pushUrl(string $url, string $caption) {
		return $this->push(new Breadcrumb($url, $caption));
	}

	/**
	 * @inheritDoc
	 */
	public function registerPushHandler(PushHandlerContract $customPushHandler) {
		$this->pushHandlers->push($customPushHandler);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getBreadcrumbs(): Collection {
		return $this->breadcrumbs;
	}

}