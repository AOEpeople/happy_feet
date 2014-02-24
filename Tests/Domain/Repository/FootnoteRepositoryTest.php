<?php
/**
 * @package HappyFeet
 * @subpackage Domain_Repository_Test
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Domain_Repository_FootnoteRepositoryTest extends tx_phpunit_database_testcase {

	/**
	 * @var Tx_HappyFeet_Domain_Repository_FootnoteRepository
	 */
	private $repository;

	/**
	 *
	 */
	public function setUp(){
		$this->repository = new Tx_HappyFeet_Domain_Repository_FootnoteRepository();
		$this->createDatabase();
		$this->useTestDatabase();
		$this->importExtensions(array('happy_feet'));
	}

	/**
	 * (non-PHPdoc)
	 * @see PHPUnit_Framework_TestCase::tearDown()
	 */
	protected function tearDown() {
		$this->dropDatabase();
		unset ($this->repository);
	}

	/**
	 * @test
	 */
	public function shouldGetLowestIndex(){
		$this->importDataSet(dirname(__FILE__) . '/fixtures/tx_happyfeet_domain_model_footnote.xml');
		$lowestIndex = $this->repository->getLowestFreeIndex();
		$this->assertEquals(1, $lowestIndex);
	}

	/**
	 * @test
	 */
	public function shouldAddIndex(){
		$this->importDataSet(dirname(__FILE__) . '/fixtures/tx_happyfeet_domain_model_footnote.xml');
		$object = new Tx_HappyFeet_Domain_Model_Footnote();
		$lowestIndex = $this->repository->add($object);
	}
} 