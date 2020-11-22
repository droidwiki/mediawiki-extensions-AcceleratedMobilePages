<?php

namespace AMP\Description;

use WikiPage;

class Noop implements PageDescription {
	public function retrieve( WikiPage $page ) {
		return '';
	}
}
