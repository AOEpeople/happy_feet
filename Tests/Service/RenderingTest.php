<?php

/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_HappyFeet_Service_RenderingTest extends Tx_Phpunit_TestCase
{

    /**
     * @var Tx_HappyFeet_Service_Rendering
     */
    private $renderingService;

    /**
     *
     */
    public function setUp()
    {
        $footnote = $this->getMock(
            'Tx_HappyFeet_Domain_Model_Footnote',
            array('getHeader', 'getDescription')
        );
        $footnote->_setProperty( 'uid', 4711 );
        $footnote->expects( $this->any() )->method( 'getHeader' )->will( $this->returnValue( 'HEADER@4711' ) );
        $footnote->expects( $this->any() )->method( 'getDescription' )->will(
            $this->returnValue( 'DESCRIPTION@4711' )
        );

        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootNoteById')
        );
        $footnoteRepository->expects( $this->any() )->method( 'getFootNoteById' )->will(
            $this->returnValue( $footnote )
        );

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->renderingService->injectFootnoteRepository( $footnoteRepository );
    }

    /**
     * @test
     */
    public function footnoteIdIsPresent()
    {
        $content = $this->renderingService->renderFootnote( 4711 );
        $this->assertRegExp( '~[^@]4711~', $content );
    }

    /**
     * @test
     */
    public function footnoteHeaderIsPresent()
    {
        $content = $this->renderingService->renderFootnote( 4711 );
        $this->assertRegExp( '~HEADER@4711~', $content );
    }

    /**
     * @test
     */
    public function footnoteDescriptionIsPresent()
    {
        $content = $this->renderingService->renderFootnote( 4711 );
        $this->assertRegExp( '~DESCRIPTION@4711~', $content );
    }
} 