<?php
/**
 * Render Footnotes for FCE
 * Created by PhpStorm.
 * User: bilal.arslan
 * Date: 21.02.14
 * Time: 12:52
 */
class Tx_HappyFeet_Service_FCEFootnoteService {

	/**
	 * @var Tx_HappyFeet_Domain_Repository_FootnoteRepository
	 */
	private $repository;
	/**
	 * @param Tx_HappyFeet_Domain_Repository_FootnoteRepository $repository
	 */
	public function __construct(Tx_HappyFeet_Domain_Repository_FootnoteRepository $repository = null) {
		if ($repository === null) {
			$this->repository = new Tx_HappyFeet_Domain_Repository_FootnoteRepository();
		} else {
			$this->repository = $repository;
		}
	}
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

		$footNotesList = $this->repository->getFootnotesByUids ( $footNotes );

		$content = '';
		foreach ( $footNotesList as $footNote ) {
			/** @var Tx_HappyFeet_Domain_Model_Footnote $foot */
			$content .= '<h2>' . $footNote->getTitle () . '</h2>';
			$content .= '<p>' . $footNote->getDescription () . '</p>';
		}

		return $content;
	}
}