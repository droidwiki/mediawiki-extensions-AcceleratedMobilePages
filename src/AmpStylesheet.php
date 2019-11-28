<?php

namespace AMP;

use CSSMin;
use WANObjectCache;

class AmpStylesheet {
	const CACHE_PREFIX = 'amp_style';
	const CSS_PATH = __DIR__ . '/resources/amp.css';

	/** @var WANObjectCache */
	private $cache;

	public function __construct( WANObjectCache $cache ) {
		$this->cache = $cache;
	}

	public function read() {
		$cssModified = filemtime( self::CSS_PATH );
		$cacheKey = $this->cache->makeGlobalKey( self::CACHE_PREFIX, $cssModified );
		$css = $this->cache->get( $cacheKey );
		if ( $css === false ) {
			$css = file_get_contents( self::CSS_PATH );
			$css = CSSMin::minify( $css );

			$this->cache->set( $cacheKey, $css );
		}

		return $css;
	}
}
