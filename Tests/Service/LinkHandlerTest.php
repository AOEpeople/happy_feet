<?php

/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Kevin Fuchs <kevin.schu@aoe.com>
 */
class Tx_HappyFeet_Typo3_Service_LinkHandlerTest extends Tx_Phpunit_TestCase
{
    /**
     * @var Tx_HappyFeet_Typo3_Service_LinkHandler
     */
    private $linkHandler;

    /**
     *
     */
    public function setUp()
    {
        $renderingService = $this->getMock( 'Tx_HappyFeet_Service_Rendering', array('renderFootnotes') );
        $renderingService->expects( $this->any() )->method( 'renderFootnotes' )->will(
            $this->returnValue( 'FOOTNOTE:4711' )
        );
        $linkHandler = $this->getMock( 'Tx_HappyFeet_Typo3_Service_LinkHandler', array('getRenderingService') );
        $linkHandler->expects( $this->any() )->method( 'getRenderingService' )->will(
            $this->returnValue( $renderingService )
        );
        $this->linkHandler = $linkHandler;
    }

    /**
     * @test
     */
    public function dismissRendereringServiceOnWrongKeyword()
    {
        $footnote = $this->linkHandler->main(
            'Lorem ipsum',
            array(),
            'WRONG KEYWORD',
            'tx_happyfeet_domain_model_footnote:4711',
            'blubber',
            new tslib_cObj()
        );
        $this->assertEquals( 'Lorem ipsum', $footnote );
    }

    /**
     * @test
     */
    public function renderingServiceIsCalledCorrectly()
    {
        $footnote = $this->linkHandler->main(
            'Lorem ipsum',
            array(),
            'happy_feet',
            'tx_happyfeet_domain_model_footnote:4711',
            'blubber',
            new tslib_cObj()
        );
        $this->assertContains( 'FOOTNOTE:4711', $footnote );
    }
} 