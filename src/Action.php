<?php

namespace AMP;

use Action as MWAction;
use IContextSource;
use MediaWiki\MediaWikiServices;
use Page;

class Action extends MWAction {
	/** @var AmpRenderer */
	private $ampRenderer;
	/** @var AmpCondition */
	private $ampCondition;

	public function __construct( Page $page, IContextSource $context = null ) {
		parent::__construct( $page, $context );
		$this->ampCondition = MediaWikiServices::getInstance()->get( 'AmpCondition' );
		$this->ampRenderer = MediaWikiServices::getInstance()->get( 'AmpRenderer' );
	}

	/**
	 * Return the name of the action this object responds to
	 * @return string Lowercase name
	 * @since 1.17
	 *
	 */
	public function getName() {
		if ( !$this->ampCondition->shouldHaveAmp( $this->getTitle() ) ) {
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

		try {
			echo $this->ampRenderer->render( $this->page );
		}
		catch ( RevisionNotFound $e ) {
			$response = $this->getRequest()->response();
			$response->statusHeader( 302 );
			$response->header( 'Location: ' . $this->getTitle()->getFullURL() );
		}
	}
}
