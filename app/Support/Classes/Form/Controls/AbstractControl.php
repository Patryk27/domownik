<?php

namespace App\Support\Classes\Form\Controls;

use App\Exceptions\Exception;
use App\Services\Logger\Contract as LoggerContract;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;

/**
 * This class provides a relatively simple method of rendering form controls.
 * ---
 * The __toString() method uses reflection to gather all the class's protected fields and then calls according getXxx()
 * or isXxx() accessor to get the field's value. Reading plain field's value is not used because some fields (like
 * 'value' from the 'HasValue' trait) can be set to be determined only lately in the accessor.
 */
abstract class AbstractControl
	implements ControlContract, Htmlable {

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @var LoggerContract
	 */
	private $logger;

	/**
	 * Returns control's view name.
	 * @return string
	 */
	abstract protected function getViewName();

	/**
	 * @param Application $app
	 * @param LoggerContract $logger
	 */
	public function __construct(
		Application $app,
		LoggerContract $logger
	) {
		$this->app = $app;
		$this->logger = $logger;
	}

	/**
	 * @return string
	 */
	public function toHtml() {
		$objectReflector = new \ReflectionClass($this);

		/**
		 * @var \ReflectionProperty[] $objectProperties
		 */
		$objectProperties = $objectReflector->getProperties(\ReflectionProperty::IS_PROTECTED);

		$view = View::make($this->getViewName());

		foreach ($objectProperties as $objectProperty) {
			$propertyName = $objectProperty->getName();

			$getterName = sprintf('get%s', $propertyName);

			if (!$objectReflector->hasMethod($getterName)) {
				$getterName = sprintf('is%s', $propertyName);
			}

			if (!$objectReflector->hasMethod($getterName)) {
				$message = sprintf('Cannot prepare view of form control \'%s\' because it does not have any getter for field \'%s\'.', $objectReflector->getName(), $propertyName);

				$this->logger->emergency($message);

				/**
				 * Yeah, yeah, __toString() cannot throw exceptions - but is there any other thing we can do?
				 * If we just let it go, view would possibly crash anyway (missing variable) and, on the other hand,
				 * assuming the variable's value to be some bogus constant can make the bug extremely hard to spot.
				 */

				/** @noinspection MagicMethodsValidityInspection */
				throw new Exception($message);
			}

			$propertyValue = call_user_func([$this, $getterName]);
			$view->with($propertyName, $propertyValue);
		}

		return $view->render();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->toHtml();
	}

}