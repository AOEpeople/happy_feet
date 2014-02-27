<?php
/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Service_FCEFootnoteServiceTest extends Tx_Phpunit_TestCase {

	/**
	 * @var Tx_HappyFeet_Service_FCEFootnoteService
	 */
	private $service;

	/**
	 * Tx_HappyFeet_Domain_Repository_FootnoteRepository
	 * @var PHPUnit_Framework_MockObject_MockObject
	 */
	private $repository;
	/**
	 * setup
	 */
	public function setUp() {
		$this->service = new Tx_HappyFeet_Service_FCEFootnoteService();
		$this->renderer = $this->getMock ( 'Tx_HappyFeet_Service_Rendering' );
	}

	/**
	 * @test
	 * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
	 */
	public function shouldRenderItemList() {
		$this->renderer->expects ( $this->any () )->method ( 'renderFootnotes' )->will ( $this->returnValue ( '' ) );
		$this->service->injectRenderingService ( $this->renderer );
		$content = $this->service->renderItemList ( '1,2' );
		$this->assertEquals ( '', $content );
	}
	/**
	 * @test
	 * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
	 */
	public function shouldRenderItemListWithConf() {
		$this->renderer->expects ( $this->any () )->method ( 'renderFootnotes' )->will ( $this->returnValue ( 'contentString' ) );
		$this->service->injectRenderingService ( $this->renderer );
		$footNotesList = array();
		$footnote = $this->getMock ( 'Tx_HappyFeet_Domain_Model_Footnote' );
		$footNotesList[] = $footnote;
		$footnote = $this->getMock ( 'Tx_HappyFeet_Domain_Model_Footnote' );
		$footnote->expects ( $this->any () )->method ( 'getDescription' )->will ( $this->returnValue ( 'D' ) );
		$footNotesList[] = $footnote;

		$conf = array('userFunc' => '', 'field' => '');
		$this->service->cObj = $this->getMock ( 'tslib_cObj' );
		$content = $this->service->renderItemList ( '1,2', $conf );

		$this->assertContains ( 'contentString', $content );
	}
}