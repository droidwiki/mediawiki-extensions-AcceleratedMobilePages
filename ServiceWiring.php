<?php

use AMP\AmpCondition;
use AMP\AmpRenderer;
use AMP\AmpStylesheet;
use MediaWiki\MediaWikiServices;

return [
	'AmpCondition' => function ( MediaWikiServices $services ) {
		return new AmpCondition();
	},
	'AmpStylesheet' => function ( MediaWikiServices $services ) {
		return new AmpStylesheet();
	},
	'AmpRenderer' => function ( MediaWikiServices $services ) {
		return new AmpRenderer( $services->get( 'AmpStylesheet' ), Title::newMainPage() );
	},
];
