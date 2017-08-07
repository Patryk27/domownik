<?php

namespace App\Console\Commands;

use App\Services\I18n\JsLocalizationGenerator;
use Illuminate\Console\Command;

class RefreshJsTranslations
	extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'app:refresh-js-translations';

	/**
	 * @var string
	 */
	protected $description = 'Refreshes the JS translation messages.';

	/**
	 * @var JsLocalizationGenerator
	 */
	protected $jsLocalizationGenerator;

	/**
	 * @param JsLocalizationGenerator $jsMessageGenerator
	 */
	public function __construct(
		JsLocalizationGenerator $jsMessageGenerator
	) {
		parent::__construct();

		$this->jsLocalizationGenerator = $jsMessageGenerator;
	}

	/**
	 * @return void
	 */
	public function handle() {
		$outputFileName = public_path('js/localization.js');

		$this->info('Creating the localization file...');

		$this->jsLocalizationGenerator
			->setLocalizationFileName($outputFileName)
			->generateLocalizationFile();

		$this->info(sprintf('Localization file has been saved to: %s', $outputFileName));
	}
}
