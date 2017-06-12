<?php

namespace App\Modules\Installer\Module;

use App\Modules\Scaffolding\Module\Director as AbstractDirector;

class Director
	extends AbstractDirector {

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return 'Installer';
	}

}
