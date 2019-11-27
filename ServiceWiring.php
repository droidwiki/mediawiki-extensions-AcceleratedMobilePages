<?php

use AMP\AmpCondition;
use MediaWiki\MediaWikiServices;

return [
	'AmpCondition' => function ( MediaWikiServices $services ) {
		return new AmpCondition();
	},
];
