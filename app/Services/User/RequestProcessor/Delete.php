<?php

namespace App\Services\User\RequestProcessor;

class Delete
	extends Common {

	/**
	 * @param int $id
	 * @return void
	 */
	public function process(int $id): void {
		$this->db->transaction(function() use ($id) {
			$this->userRepository->delete($id);
		});
	}
	
}