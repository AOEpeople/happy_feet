<?php
/**
 * @package HappyFeet
 * @subpackage Service_Test
 */
class Tx_HappyFeet_Service_FCEFootnoteIdServiceTest extends Tx_Phpunit_TestCase {

	/**
	 * @var Tx_HappyFeet_Service_FCEIdFootnoteService
	 */
	private $service;

	/**
	 *
	 */
	public function setUp(){
		$this->service = new Tx_HappyFeet_Service_FCEFootnoteIdService();
	}
	/**
	 * test method getFootIds
	 * @test
	 * @method Tx_HappyFeet_Service_FCEFootnoteService:getFootIds
	 */
	public function shouldGetFootnoteIds(){
		$foonoteIds = $this->service->getFootnoteIds('');
		$this->assertEquals('', $foonoteIds);
	}
} 