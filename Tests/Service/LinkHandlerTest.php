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
        $renderingService = $this->getMock('Tx_HappyFeet_Service_Rendering', array('renderFootnotes'));
        $renderingService->expects($this->any())->method('renderFootnotes')->will(
            $this->returnValue('FOOTNOTE:4711')
        );
        $linkHandler = $this->getMock('Tx_HappyFeet_Typo3_Service_LinkHandler', array('getRenderingService'));
        $linkHandler->expects($this->any())->method('getRenderingService')->will(
            $this->returnValue($renderingService)
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
        $this->assertEquals('Lorem ipsum', $footnote);
    }

    /**
     * @test
     */
    public function renderingServiceIsCalledCorrectly()
    {
        $footnote = $this->linkHandler->main(
            'Lorem ipsum',
            array(),
            'happyfeet',
            'tx_happyfeet_domain_model_footnote:4711',
            'blubber',
            new tslib_cObj()
        );
        $this->assertContains('FOOTNOTE:4711', $footnote);
    }
}