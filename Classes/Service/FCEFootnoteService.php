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
 * Render Footnotes for FCE
 */
class Tx_HappyFeet_Service_FCEFootnoteService extends Tx_HappyFeet_Service_Abstract {
	/**
	 * @var Tx_HappyFeet_Service_Rendering
	 */
	private $footnoteRenderer;

	/**
	 *
	 * @param string $footnoteUids comma separated list of uid's of the "tx_aoefootnote_item" record
	 * @param array $conf optional (this will be automatically set, of this method is called via 'TYPOSCRIPT-userFunc')
	 * @throws UnexpectedValueException
	 * @return string The wrapped index value
	 */
	public function renderItemList($footnoteUids, $conf = array()) {
		if (array_key_exists ( 'userFunc', $conf ) && array_key_exists ( 'field', $conf )) {
			$footnoteUids = $this->cObj->getCurrentVal ();
		}
		$footNotes = explode ( ',', $footnoteUids );
		if (is_array ( $footNotes ) && count ( $footNotes ) > 0) {
			return $this->getRenderingService ()->renderFootnotes ( $footNotes );
		}
		return '';
	}

	/**
	 * @param Tx_HappyFeet_Service_Rendering $footnoteRenderer
	 */
	public function injectRenderingService(Tx_HappyFeet_Service_Rendering $footnoteRenderer) {
		$this->footnoteRenderer = $footnoteRenderer;
	}

	/**
	 * @return Tx_HappyFeet_Service_Rendering
	 */
	private function getRenderingService() {
		if (!$this->footnoteRenderer instanceof Tx_HappyFeet_Service_Rendering) {
			$this->footnoteRenderer = $this->getObjectManager ()->get ( 'Tx_HappyFeet_Service_Rendering' );
		}
		return $this->footnoteRenderer;
	}
}