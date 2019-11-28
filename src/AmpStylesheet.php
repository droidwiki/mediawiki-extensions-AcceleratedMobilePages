<?php

namespace AMP;

class AmpStylesheet {
	public function read() {
		return file_get_contents( __DIR__ . '/resources/amp.css' );
	}
}
