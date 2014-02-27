<?php
/**
 * Render Footnotes for FCE
 * Created by PhpStorm.
 * User: bilal.arslan
 * Date: 21.02.14
 * Time: 12:52
 */
class Tx_HappyFeet_Service_FCEFootnoteIdService {

	/**
	 * @param string $footnoteUids
	 * @param array $conf
	 * @return string
	 */
	public function getFootIds($footnoteUids, $conf = array()) {
		$footnoteUids = '';
		if (array_key_exists ( 'userFunc', $conf ) && array_key_exists ( 'field', $conf )) {
			$footnoteUids = $this->cObj->data['field_footnote_content'];
		}
		return $footnoteUids;
	}
}