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
	 *
	 */
	public function setUp(){
		$this->repository = $this->getMock('Tx_HappyFeet_Domain_Repository_FootnoteRepository');
		$this->service = new Tx_HappyFeet_Service_FCEFootnoteService($this->repository);
	}

	/**
	 * @test
	 * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
	 */
	public function shouldRenderItemList(){
		$content = $this->service->renderItemList('1,2');
		$this->assertEquals('', $content);
	}
	/**
	 * @test
	 * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
	 */
	public function shouldRenderItemListWithConf() {
		$footNotesList = array();
		$footnote = $this->getMock('Tx_HappyFeet_Domain_Model_Footnote');
		$footnote->expects($this->any())->method('getTitle')->will($this->returnValue('A'));
		$footnote->expects($this->any())->method('getDescription')->will($this->returnValue('B'));
		$footNotesList[] = $footnote;
		$footnote = $this->getMock('Tx_HappyFeet_Domain_Model_Footnote');
		$footnote->expects($this->any())->method('getTitle')->will($this->returnValue('C'));
		$footnote->expects($this->any())->method('getDescription')->will($this->returnValue('D'));
		$footNotesList[] = $footnote;
		$this->repository->expects($this->any())->method('getFootnotesByUids')->will($this->returnValue($footNotesList));
		$conf = array('userFunc'=>'', 'field'=>'');
		$this->service->cObj = $this->getMock('tslib_cObj');
		$content = $this->service->renderItemList('1,2', $conf);
		$this->assertContains('A',$content);
		$this->assertContains('B',$content);
		$this->assertContains('C',$content);
		$this->assertContains('D',$content);
	}
} 