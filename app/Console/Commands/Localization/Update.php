<?php

namespace App\Console\Commands\Localization;

use App\Services\I18n\JsLocalizationGenerator;
use Illuminate\Console\Command;

class Update
	extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'localization:update';

	/**
	 * @var string
	 */
	protected $description = 'Updates the JS translation messages.';

	/**
	 * @var JsLocalizationGenerator
	 */
	protected $jsMessageGenerator;

	/**
	 * Update constructor.
	 * @param JsLocalizationGenerator $jsMessageGenerator
	 */
	public function __construct(
		JsLocalizationGenerator $jsMessageGenerator
	) {
		parent::__construct();
		$this->jsMessageGenerator = $jsMessageGenerator;
	}

	/**
	 * @return void
	 */
	public function handle() {
		$outputFileName = public_path('js\\localization.js');

		$this->info('Creating the localization file...');

		$this->jsMessageGenerator
			->setLocalizationFileName($outputFileName)
			->generateLocalizationFile();

		$this->info(sprintf('Localization file has been saved to: %s', $outputFileName));
	}
}
