<?php

use AMP\AmpCondition;
use AMP\AmpRenderer;
use AMP\AmpStylesheet;
use AMP\Description\Noop;
use AMP\Description\TextExtracts;
use MediaWiki\MediaWikiServices;

return [
	'AmpCondition' => function () {
		return new AmpCondition();
	},
	'AmpStylesheet' => function ( MediaWikiServices $services ) {
		return new AmpStylesheet( $services->getMainWANObjectCache() );
	},
	'AmpRenderer' => function ( MediaWikiServices $services ) {
		return new AmpRenderer( $services->get( 'AmpStylesheet' ),
			$services->get( 'PageDescription' ), Title::newMainPage() );
	},
	'PageDescription' => function () {
		if ( ExtensionRegistry::getInstance()->isLoaded( 'TextExtracts' ) ) {
			return new TextExtracts();
		} else {
			return new Noop();
		}
	},
];
