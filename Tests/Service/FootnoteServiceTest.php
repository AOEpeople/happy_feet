<?php
/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Service_FootnoteServiceTest extends Tx_Phpunit_TestCase {

	/**
	 * @var Tx_HappyFeet_Service_FootnoteService
	 */
	private $service;

	/**
	 *
	 */
	public function setUp(){
		$this->service = new Tx_HappyFeet_Service_FootnoteService();
	}

	/**
	 * @test
	 */
	public function shouldRender(){
		$content = $this->service->render('1,2');
		$this->assertContains('<br/>Die Ausgewählte Uid ist: 1<br/>Die Ausgewählte Uid ist: 2', $content);
	}
} 