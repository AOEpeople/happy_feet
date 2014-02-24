<?php
/**
 * @package HappyFeet
 * @subpackage Domain_Model_Test
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */

class Tx_HappyFeet_Domain_Model_FootnoteTest extends Tx_Phpunit_TestCase {

	/**
	 * @var Tx_HappyFeet_Domain_Model_Footnote
	 */
	private $footnote;

	/**
	 *
	 */
	public function setUp(){
		$this->footnote = new Tx_HappyFeet_Domain_Model_Footnote();
	}

	/**
	 * @test
	 */
	public function shouldSetTitle(){
		$this->footnote->setTitle('Dummy title');
		$this->assertEquals('Dummy title', $this->footnote->getTitle());
	}
} 