<?php

namespace AMP;

use Article;
use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use RequestContext;
use Title;

class ActionTest extends MediaWikiIntegrationTestCase {
	/**
	 * @covers \AMP\Action::show
	 */
	public function testExportAmpHtml() {
		$context = new RequestContext();
		$article = new Article( Title::newFromText( 'UTPage' ) );
		MediaWikiServices::getInstance()
			->getParserCache()
			->save( $article->getParserOutput(), $article->getPage(),
				$article->makeParserOptions( $context ) );
		$action = new Action( $article, $context );

		$action->show();
		$ampHtml = ob_get_clean();

		$this->assertNotEquals( '', $ampHtml );
		file_put_contents( __DIR__ . '/../../../amp.html', $ampHtml );
	}
}
