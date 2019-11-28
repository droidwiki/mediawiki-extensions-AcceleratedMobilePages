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
		$content = $article->getParserOutput();
		$content->setText( $content->getText() . '
<form name="searchbox" action="/wiki/Special:Search">
<input name="search" type="text" value="" />
<input type="submit" name="go" class="mw-ui-button" value="Search" />
</form>' );
		MediaWikiServices::getInstance()
			->getParserCache()
			->save( $content, $article->getPage(), $article->makeParserOptions( $context ) );
		$action = new Action( $article, $context );

		$action->show();
		$ampHtml = ob_get_clean();

		$this->assertNotEquals( '', $ampHtml );
		file_put_contents( __DIR__ . '/../../../amp.html', $ampHtml );
	}
}
