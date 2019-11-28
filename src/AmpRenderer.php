<?php

namespace AMP;

use ApiMain;
use ApiResult;
use Article;
use ExtensionRegistry;
use FauxRequest;
use HtmlArmor;
use MediaWiki\MediaWikiServices;
use MessageLocalizer;
use ParserOutput;
use TemplateParser;
use Title;

class AmpRenderer {
	/** @var Title */
	private $mainPage;
	/** @var AmpStylesheet */
	private $ampStylesheet;

	public function __construct( AmpStylesheet $ampStylesheet, Title $mainPage ) {
		$this->mainPage = $mainPage;
		$this->ampStylesheet = $ampStylesheet;
	}

	/**
	 * @param Article $article
	 * @return string
	 * @throws RevisionNotFound
	 */
	public function render( Article $article ) {
		$parserOutput = $article->getParserOutput();

		if ( $parserOutput === false ) {
			throw new RevisionNotFound();
		}

		$title = $parserOutput->getTitleText();

		$templates = new TemplateParser( __DIR__ . '/templates' );
		$params = [
			'html-meta-description' => $this->metaDescription( $article ),
			'stylesheet' => $this->ampStylesheet->read(),
			'canonical-url' => $article->getTitle()->getCanonicalUrl(),
			'title' => $title,
			'main-page-url' => $this->mainPage->getLinkURL(),
			'logo-url' => $article->getContext()->getSkin()->getLogo(),
			'site-name' => $article->getContext()->getConfig()->get( 'Sitename' ),
			'article-url' => $article->getTitle()->getLocalURL(),
			'article-message' => $article->getContext()->msg( 'nstab-main' ),
			'talk-url' => $article->getTitle()->getTalkPageIfDefined()->getLocalURL(),
			'talk-message' => $article->getContext()->msg( 'talk' ),
			'history-url' => $article->getTitle()->getLocalURL(
				$options = [ 'action' => 'history' ] ),
			'history-message' => $article->getContext()->msg( 'history' ),
			'page-content' => $this->pageContent( $parserOutput ),
			'edit-url' => $article->getTitle()->getLocalURL( $article->getContext()
				->getSkin()
				->editUrlOptions() ),
			'edit-message' => $article->getContext()->msg( 'edit' ),
			'category-links' => $this->categoryList( $article->getContext(),
				$article->getContext()->getLanguage(), $parserOutput ),
			'copyright' => $article->getContext()->getSkin()->getCopyright(),
			'about-link' => $article->getContext()->getSkin()->aboutLink(),
			'disclaimer-link' => $article->getContext()->getSkin()->disclaimerLink(),
			'privacy-link' => $article->getContext()->getSkin()->privacyLink()
		];

		return $templates->processTemplate( 'amp', $params );
	}

	private function metaDescription( Article $article ) {
		if ( ExtensionRegistry::getInstance()->isLoaded( 'TextExtracts' ) ) {
			$id = $article->getId();
			$api = new ApiMain( new FauxRequest( [
				'action' => 'query',
				'prop' => 'extracts',
				'explaintext' => true,
				'exintro' => true,
				'exsentences' => 1,
				'exlimit' => 1,
				'pageids' => $id,
			] ) );
			$api->execute();
			$data = $api->getResult()->getResultData( [ 'query', 'pages' ] );
			$contentKey = $data[$id]['extract'][ApiResult::META_CONTENT] ?? '*';
			if ( isset( $data[$id]['extract'][$contentKey] ) ) {
				return $data[$id]['extract'][$contentKey];
			}
		}

		return '';
	}

	private function pageContent( ParserOutput $parserOutput ) {
		$text = $parserOutput->getText( [
			'allowTOC' => false,
			'enableSectionEditLinks' => false,
		] );
		$text = str_replace( '<img', '<amp-img', $text );
		// decoding is not supported on amp-img
		$text = str_replace( 'decoding="async"', '', $text );
		// mostly for tests: remove NewPP comments with changing timestamps
		$text = preg_replace( '/<!--(.|\s)*?-->(\n)?/', '', $text );

		return $text;
	}

	private function categoryList(
		MessageLocalizer $localizer, \Language $language, ParserOutput $parserOutput
	) {
		$catLinks = [];
		$categories = $parserOutput->getCategoryLinks();
		$link = MediaWikiServices::getInstance()->getLinkRenderer();
		foreach ( $categories as $key => $category ) {
			$catLinks[] =
				$link->makeKnownLink( Title::newFromText( $category, NS_CATEGORY ),
					new HtmlArmor( $category ) );
		}

		return $localizer->msg( 'categories' ) . ': ' . $language->commaList( $catLinks );
	}
}
