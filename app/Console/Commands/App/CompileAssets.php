<?php

namespace App\Console\Commands\App;

use App\Services\Asset\CompilerContract as AssetCompilerContract;
use Illuminate\Console\Command;

class CompileAssets
	extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'app:compile-assets';

	/**
	 * @var string
	 */
	protected $description = 'Compiles all the application\'s internal assets.';

	/**
	 * @var AssetCompilerContract
	 */
	protected $assetCompiler;

	/**
	 * @param AssetCompilerContract $assetCompiler
	 */
	public function __construct(
		AssetCompilerContract $assetCompiler
	) {
		parent::__construct();

		$this->assetCompiler = $assetCompiler;
	}

	/**
	 * @return void
	 */
	public function handle() {
		$this->assetCompiler->compileAssets();
	}

}
