<?php
/**
 * @package HappyFeet
 * @subpackage Domain_Repository_Test
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Domain_Repository_FootnoteRepositoryTest extends tx_phpunit_database_testcase
{

    /**
     * @var Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    private $repository;

    /**
     *
     */
    public function setUp()
    {
        $this->repository = new Tx_HappyFeet_Domain_Repository_FootnoteRepository();
        $this->createDatabase();
        $this->useTestDatabase();
        $this->importExtensions( array('happy_feet') );
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        //$this->dropDatabase();
        unset ( $this->repository );
    }

    /**
     * @test
     */
    public function shouldGetDefaultIndexWhenNoRecordsAvailable()
    {
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertEquals( 1, $lowestIndex );
    }
    /**
     * @test
     */
    public function shouldGetLowestIndex()
    {
        $this->importDataSet( dirname( __FILE__ ) . '/fixtures/tx_happyfeet_domain_model_footnote.xml' );
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertEquals( 1, $lowestIndex );
    }

    /**
     * @test
     */
    public function shouldGetIndexWithGap()
    {
        $this->importDataSet( dirname( __FILE__ ) . '/fixtures/tx_happyfeet_domain_model_footnote_gap.xml' );
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertEquals( 2, $lowestIndex );
    }

    /**
     * @test
     */
    public function shouldGetNextIndexInRow()
    {
        $this->importDataSet( dirname( __FILE__ ) . '/fixtures/tx_happyfeet_domain_model_footnote_row.xml' );
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertEquals( 3, $lowestIndex );
    }

    /**
     * @test
     * @expectedException Tx_Extbase_Persistence_Exception_IllegalObjectType
     */
    public function shouldThrowExceptionWithInvalidObject()
    {
        $footnote = new stdClass();
        $this->repository->add( $footnote );
    }
}