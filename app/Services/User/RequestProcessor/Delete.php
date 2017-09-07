<?php

namespace App\Services\User\RequestProcessor;

class Delete
	extends Base {

	/**
	 * @param int $id
	 * @return void
	 */
	public function process(int $id): void {
		$this->db->transaction(function () use ($id) {
			$this->log->info('Deleting user with id [%d].', $id);

			$this->userRepository->delete($id);
		});
	}

}