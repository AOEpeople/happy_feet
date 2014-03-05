<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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
        $this->repository->initializeObject();
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
     */
    public function shouldGetFootnotesByUids()
    {
        $this->importDataSet( dirname( __FILE__ ) . '/fixtures/tx_happyfeet_domain_model_footnote_collection.xml' );
        $footnotes = $this->repository->getFootnotesByUids( array(2, 4) );
        $this->assertCount( 2, $footnotes );
        $this->assertEquals( 2, $footnotes[0]->getUid() );
        $this->assertEquals( 4, $footnotes[1]->getUid() );
    }

    /**
     * @test
     */
    public function shouldSortFootnotesByGivenOrderOfUids()
    {
        $this->importDataSet( dirname( __FILE__ ) . '/fixtures/tx_happyfeet_domain_model_footnote_collection.xml' );
        $footnotes = $this->repository->getFootnotesByUids( array(4, 1, 5, 3, 2) );
        $this->assertCount( 5, $footnotes );
        $this->assertEquals( 4, $footnotes[0]->getUid() );
        $this->assertEquals( 1, $footnotes[1]->getUid() );
        $this->assertEquals( 5, $footnotes[2]->getUid() );
        $this->assertEquals( 3, $footnotes[3]->getUid() );
        $this->assertEquals( 2, $footnotes[4]->getUid() );
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