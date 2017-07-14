<?php

namespace App\Services\Section;

class Manager
	implements ManagerContract {

	/**
	 * @inheritDoc
	 */
	public function getSectionNames(): array {
		// @todo should it be dynamic?

		return [
			'dashboard',
			'finances',
		];
	}

}