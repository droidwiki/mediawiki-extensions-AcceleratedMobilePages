<?php

namespace AMP;

use MediaWiki\MediaWikiServices;
use OutputPage;

class Hooks {
	public static function onBeforePageDisplay( OutputPage $out ) {
		/** @var AmpCondition $condition */
		$condition = MediaWikiServices::getInstance()->get( 'AmpCondition' );
		if ( !$condition->shouldHaveAmp( $out->getTitle() ) ) {
			return;
		}

		$out->addHeadItem( 'amphtml', self::ampLink( $out ) );
	}

	private static function ampLink( OutputPage $out ): string {
		return '<link rel="amphtml" href="' . wfExpandUrl( $out->getTitle()->getLinkURL( [
				'action' => 'amp',
			] ) ) . '">';
	}
}
