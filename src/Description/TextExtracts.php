<?php

namespace AMP\Description;

use ApiMain;
use ApiResult;
use FauxRequest;
use WikiPage;

class TextExtracts implements PageDescription {
	public function retrieve( WikiPage $page ) {
		$id = $page->getId();
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
		return '';
	}
}
