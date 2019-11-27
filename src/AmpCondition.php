<?php

namespace AMP;

use MediaWiki\Linker\LinkTarget;

class AmpCondition {
	public function shouldHaveAmp( LinkTarget $target ): bool {
		return $target->inNamespace( NS_MAIN );
	}
}
