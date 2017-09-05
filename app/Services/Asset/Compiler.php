<?php

namespace App\Services\Asset;

use App\Services\Asset\Compiler\LocalizationCompiler;
use Symfony\Component\Console\Output\ConsoleOutput;

class Compiler
	implements CompilerContract {

	/**
	 * @var ConsoleOutput
	 */
	protected $console;

	/**
	 * @var LocalizationCompiler
	 */
	protected $localizationCompiler;

	/**
	 * @param ConsoleOutput $console
	 * @param LocalizationCompiler $localizationCompiler
	 */
	public function __construct(
		ConsoleOutput $console,
		LocalizationCompiler $localizationCompiler
	) {
		$this->console = $console;
		$this->localizationCompiler = $localizationCompiler;
	}

	/**
	 * @inheritDoc
	 */
	public function compileAssets() {
		$this->console->writeln('Compiling internal assets:');

		$this->localizationCompiler->compile();

		$this->console->writeln('<info>All the internal assets have been compiled.</info>');
	}

}