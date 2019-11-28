<?php

namespace AMP\Description;

use Article;

class Noop implements PageDescription {
	public function retrieve( Article $article ) {
		return '';
	}
}
