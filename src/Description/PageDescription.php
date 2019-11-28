<?php

namespace AMP\Description;

use Article;

interface PageDescription {
	public function retrieve( Article $article );
}
