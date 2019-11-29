<?php

namespace AMP;

use Article;
use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use RequestContext;
use Title;

class ActionTest extends MediaWikiIntegrationTestCase {
	private $context;
	private $article;

	/**
	 * @covers \AMP\Action::show
	 */
	public function testExportAmpHtml() {
		$this->addContent( '
<form name="searchbox" action="/wiki/Special:Search">
<input name="search" type="text" value="" />
<input type="submit" name="go" class="mw-ui-button" value="Search" />
</form>' );
		$action = new Action( $this->article, $this->context );

		$action->show();
		$ampHtml = ob_get_clean();

		$this->assertNotEquals( '', $ampHtml );
		file_put_contents( __DIR__ . '/../../../amp.html', $ampHtml );
	}

	private function addContent( $additionalContent ): void {
		$content = $this->article->getParserOutput();
		$content->setText( $content->getText() . $additionalContent );

		MediaWikiServices::getInstance()
			->getParserCache()
			->save( $content, $this->article->getPage(),
				$this->article->makeParserOptions( $this->context ) );
	}

	/**
	 * @covers \AMP\Action::show
	 */
	public function testDoesNotIncludeIframes() {
		$this->addContent( '
<iframe
	title="Play video"
	src="//www.youtube.com/embed/T7USply-sxw?"
	width="640"
	height="360"
	frameborder="0"
	allowfullscreen="true"></iframe>' );
		$action = new Action( $this->article, $this->context );

		$action->show();
		$ampHtml = ob_get_clean();

		$this->assertStringNotContainsString( 'iframe', $ampHtml );
	}

	public function setUp(): void {
		parent::setUp();
		$this->context = new RequestContext();
		$this->article = new Article( Title::newFromText( 'UTPage' ) );
		$content = $this->article->getParserOutput();
		$content->addCategory( 'Test-Category', 'TEST-CATEGORY' );

		MediaWikiServices::getInstance()
			->getParserCache()
			->save( $content, $this->article->getPage(),
				$this->article->makeParserOptions( $this->context ) );
	}
}
