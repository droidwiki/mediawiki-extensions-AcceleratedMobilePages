<?php

namespace AMP;

use MediaWikiIntegrationTestCase;
use NamespaceInfo;
use Title;

class AmpConditionTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers       \AMP\AmpCondition::shouldHaveAmp
	 * @dataProvider provideNamespaces
	 */
	public function testHasAmpPage( $ns, $expected ) {
		$title = Title::newFromText( 'ContentPage', $ns );
		$condition = new AmpCondition();

		$this->assertEquals( $expected, $condition->shouldHaveAmp( $title ) );
	}

	public function provideNamespaces() {
		$namespaces = [];
		foreach ( NamespaceInfo::CANONICAL_NAMES as $ns => $alias ) {
			if ( $ns === NS_MAIN ) {
				continue;
			}
			$namespaces[$alias] = [ $ns, false ];
		}
		$namespaces['Main'] = [ NS_MAIN, true ];

		return $namespaces;
	}
}
