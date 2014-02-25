<?php

/**
 * Tests for class Tx_HappyFeet_Typo3_Hooks_Tcemain.
 *
 * @package HappyFeet
 * @subpackage Typo3_Hooks_Test
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Typo3_Hooks_TcemainTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_HappyFeet_Typo3_Hooks_Tcemain
     */
    private $tcemainHook;

    /**
     * @return void
     */
    public function setUp()
    {
        $footnoteRepository = $this->getMock('Tx_HappyFeet_Domain_Repository_FootnoteRepository', array('getLowestFreeIndex'));
        $footnoteRepository->expects($this->any())->method('getLowestFreeIndex')->will($this->returnValue(1));
        $this->tcemainHook = $this->getMock('Tx_HappyFeet_Typo3_Hooks_Tcemain', array('getFootnoteRepository'));
        $this->tcemainHook->expects($this->any())->method('getFootnoteRepository')->will($this->returnValue($footnoteRepository));
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithNewFootnote()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfeet_domain_model_footnote',
            null,
            $fieldArray,
            null
        );
        $this->assertArrayHasKey('index_number', $fieldArray);
        $this->assertEquals(1, $fieldArray['index_number']);
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithExistingFootnote()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'foo',
            'tx_happyfeet_domain_model_footnote',
            null,
            $fieldArray,
            null
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithOtherTable()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfoo_domain_model_baz',
            null,
            $fieldArray,
            null
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }
}