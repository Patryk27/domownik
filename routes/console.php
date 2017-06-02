<?php

Artisan::command('ping', function() {
	$this->comment('Pong!');
})->describe('Play a simple ping-pong text game.');