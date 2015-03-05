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
     * Set up test case
     */
    public function setUp()
    {
        $footnote1 = $this->getMock(
            'Tx_HappyFeet_Domain_Model_Footnote',
            array('getHeader', 'getDescription', 'getIndexNumber')
        );
        $footnote1->_setProperty('uid', 4711);
        $footnote1->expects($this->any())->method('getHeader')->will($this->returnValue('HEADER@4711'));
        $footnote1->expects($this->any())->method('getIndexNumber')->will($this->returnValue('4711'));
        $footnote1->expects($this->any())->method('getDescription')->will(
            $this->returnValue('DESCRIPTION@4711')
        );
        $footnote2 = $this->getMock(
            'Tx_HappyFeet_Domain_Model_Footnote',
            array('getHeader', 'getDescription', 'getIndexNumber')
        );
        $footnote2->_setProperty('uid', 4712);
        $footnote2->expects($this->any())->method('getHeader')->will($this->returnValue('HEADER@4712'));
        $footnote2->expects($this->any())->method('getIndexNumber')->will($this->returnValue('4712'));
        $footnote2->expects($this->any())->method('getDescription')->will(
            $this->returnValue('DESCRIPTION@4712')
        );

        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootnotesByUids'),
            array(),
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getFootnotesByUids')->will(
            $this->returnValue(array($footnote1, $footnote2))
        );

        $this->renderingService = $this->getMock('Tx_HappyFeet_Service_Rendering', array('getFootnoteRepository'));
        $this->renderingService->expects($this->any())->method('getFootnoteRepository')->will(
            $this->returnValue($footnoteRepository)
        );
    }

    /**
     * @test
     */
    public function shouldNotRenderWithNoUids()
    {
        $content = $this->renderingService->renderFootnotes(array());
        $this->assertEquals('', $content);
    }

    /**
     * @test
     */
    public function shouldNotRenderWhenNoFootnotesAvailable()
    {
        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootnotesByUids'),
            array(),
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getFootnotesByUids')->will(
            $this->returnValue(array())
        );

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes(array());
        $this->assertEquals('', $content);
    }

    /**
     * @test
     */
    public function footnoteIdIsPresent()
    {
        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~[^@]4711~', $content);
        $this->assertRegExp('~[^@]4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteHeaderIsPresent()
    {
        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~HEADER@4711~', $content);
        $this->assertRegExp('~HEADER@4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteDescriptionIsPresent()
    {
        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~DESCRIPTION@4711~', $content);
        $this->assertRegExp('~DESCRIPTION@4712~', $content);
    }
}