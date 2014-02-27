<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2014 AOE GmbH <dev@aoe.com>
 * All rights reserved
 *
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Render Footnote ids for FCE
 */
class Tx_HappyFeet_Service_FCEFootnoteIdService {

	/**
	 * @param string $footnoteUids
	 * @param array $conf
	 * @return string
	 */
	public function getFootnoteIds($footnoteUids, $conf = array()) {
		$footnoteUids = '';
		if (array_key_exists ( 'userFunc', $conf ) && array_key_exists ( 'field', $conf )) {
			$footnoteUids = $this->cObj->data['field_footnote_content'];
		}
		return $footnoteUids;
	}
}