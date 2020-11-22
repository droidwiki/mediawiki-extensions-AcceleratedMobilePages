<?php

namespace AMP\Description;

use WikiPage;

interface PageDescription {
	public function retrieve( WikiPage $page );
}
