<?php

namespace Database\Seeds\Base;

use Illuminate\Console\Command as ConsoleCommand;
use Illuminate\Container\Container;

abstract class Seeder {

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @var ConsoleCommand
	 */
	protected $command;

	/**
	 * @param Container $container
	 */
	public function __construct(
		Container $container
	) {
		$this->container = $container;
	}

	/**
	 * @return void
	 */
	abstract public function run(): void;

	/**
	 * @return ConsoleCommand
	 */
	public function getCommand(): ConsoleCommand {
		return $this->command;
	}

	/**
	 * @param ConsoleCommand $command
	 * @return Seeder
	 */
	public function setCommand(ConsoleCommand $command): Seeder {
		$this->command = $command;
		return $this;
	}

	/**
	 * @param string $className
	 * @return $this
	 */
	protected function call(string $class) {
		if (isset($this->command)) {
			$this->command->getOutput()->writeln(sprintf('<info>Seeding:</info> %s', $class));
		}

		$this->resolve($class)->__invoke();

		return $this;
	}

	/**
	 * @param string $class
	 * @return mixed
	 */
	private function resolve(string $class) {
		$instance = $this->container->make($class);
		$instance->setContainer($this->container);

		if (isset($this->command)) {
			$instance->setCommand($this->command);
		}

		return $instance;
	}

}