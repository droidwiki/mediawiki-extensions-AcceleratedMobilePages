<?php

namespace AMP;

use Action as MWAction;
use ApiMain;
use ApiResult;
use ExtensionRegistry;
use FauxRequest;
use HtmlArmor;
use MediaWiki\MediaWikiServices;
use ParserOutput;
use Title;

class Action extends MWAction {

	/**
	 * Return the name of the action this object responds to
	 * @return string Lowercase name
	 * @since 1.17
	 *
	 */
	public function getName() {
		/** @var AmpCondition $condition */
		$condition = MediaWikiServices::getInstance()->get( 'AmpCondition' );
		if ( !$condition->shouldHaveAmp( $this->getTitle() ) ) {
			return 'nosuchaction';
		}
		return "amp";
	}

	/**
	 * The main action entry point.  Do all output for display and send it to the context
	 * output. Do not use globals $wgOut, $wgRequest, etc, in implementations; use
	 * $this->getOutput(), etc.
	 * @since 1.17
	 */
	public function show() {
		$this->getOutput()->disable();

		$parserOutput = $this->page->getParserOutput();
		$mainPageLink = Title::newMainPage()->getLinkURL();
		$title = $parserOutput->getTitleText();

		echo '
<!doctype html>
<html ⚡ lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    ' . $this->metaDescription() . '
    <link rel="preload" as="script" href="https://cdn.ampproject.org/v0.js">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-auto-ads" src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js"></script>
    <style amp-custom>
        html {
    	    font-family: sans-serif;
    		font-size: 0.875em;
    		line-height: 1.6;
    	}
    	
    	header {
    		display: flex;
    		justify-content: space-around;
    		align-items: center;
    		height: 64px;
    		box-shadow: 0 3px 3px 2px rgba(0,0,0,0.075), 0 0 2px rgba(0,0,0,0.2);
    	}
    	
    	header a {
    		color: black;
    	}
    	
    	article, footer {
    		padding: 1.25em 1.5em 1.5em 1.5em;
    		margin: 0 auto;
    	}
    	
    	article {
    		max-width: 840px;
    	}
    	
    	.actions {
			display: flex;
			justify-content: space-around;
			margin-bottom: 1em;
    	}
    	
    	.actions a {
    		background-color: #f8f9fa;
			color: #222;
			border-color: #a2a9b1;
			border-style: solid;
			border-width: 1px;
			border-radius: 2px;
			padding: 6px 12px;
			position: relative;
			min-height: 2.28571429em;
			font-weight: bold;
			text-decoration: none;
			vertical-align: top;
			text-align: center;
			cursor: pointer;
			display: inline-block;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			font-family: inherit;
			font-size: inherit;
			white-space: nowrap;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
    	}
    	
    	footer {
    		background-color: #a2a9b1;
    	}
    	
    	.thumb.tright {
    		float: right;
    		clear: right;
    		margin: 0.5em 0 1.3em 1.4em
    	}
    	
    	.wikitable {
			background-color: #f8f9fa;
			color: #222;
			margin: 1em 0;
			border: 1px solid #a2a9b1;
			border-collapse: collapse;
    	}
    	
    	.wikitable td {
    	    border: 1px solid #a2a9b1;
    		padding: 0.2em 0.4em;
    	}
    	
    	table {
    		display: block;
    		width: 100%;
			overflow: auto;
			overflow-y: hidden;
    	}
    	
    	a {
    		color: #0645ad;
    		text-decoration: none;
    	}
    	
    	.site-links {
    		margin-top: 1em;
    		display: flex;
    		justify-content: space-between;
    	}
    </style>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

    <link rel="canonical" href="' . $this->getTitle()->getCanonicalUrl() . '">
    <title>' . $title . '</title>
</head>
<body>
	<amp-auto-ads type="adsense" data-ad-client="ca-pub-4622825295514928"></amp-auto-ads>
	<header>
		<a href="' . $mainPageLink . '">
			' . $this->getContext()->getConfig()->get( 'Sitename' ) . '
		</a>
		<a href="' . $mainPageLink . '">
			<amp-img alt="logo" width="48px" height="48px" src="' . $this->getSkin()->getLogo() . '"></amp-img>
		</a>
	</header>
	<article>
		<h1>' . $title . '</h1>
		' . $this->pageContent( $parserOutput ) . '
		<div class="actions">
			<a href="' . $this->getTitle()->getLocalURL( $this->getSkin()->editUrlOptions() ) . '">
				' . $this->msg( 'edit' ) . '
			</a>
			<a href="' . $this->getTitle()->getLocalURL( $options = [ 'action' => 'history' ] ) . '">
				' . $this->msg( 'history' ) . '
			</a>
		</div>
		<div class="catlinks">
			' . $this->categoryList( $parserOutput ) . '
		</div>
	</article>
	<footer>
		<div>' . $this->getSkin()->getCopyright() . '</div>
		<div class="site-links">
			<span>' . $this->getSkin()->aboutLink() . '</span>
			<span>' . $this->getSkin()->disclaimerLink() . '</span>
			<span>' . $this->getSkin()->privacyLink() . '</span>
		</div>
	</footer>
</body>
</html>
		';
	}

	private function metaDescription() {
		if ( ExtensionRegistry::getInstance()->isLoaded( 'TextExtracts' ) ) {
			$id = $this->page->getId();
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
				return '<meta name="description" content="' . $data[$id]['extract'][$contentKey] .
					'">';
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

		return $text;
	}

	private function categoryList( ParserOutput $parserOutput ) {
		$catLinks = [];
		$categories = $parserOutput->getCategoryLinks();
		$link = MediaWikiServices::getInstance()->getLinkRenderer();
		foreach ( $categories as $key => $category ) {
			$catLinks[] =
				$link->makeKnownLink( Title::newFromText( $category, NS_CATEGORY ),
					new HtmlArmor( $category ) );
		}

		return $this->msg( 'categories' ) . ': ' . $this->getLanguage()->commaList( $catLinks );
	}
}
