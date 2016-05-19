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
class Tx_HappyFeet_Tests_Unit_Service_RenderingTest extends PHPUnit_Framework_TestCase
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

        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertEquals('', $content);
    }

    /**
     * @test
     */
    public function footnoteIdIsPresent()
    {
        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootnotesByUids'),
            array(),
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getFootnotesByUids')->will(
            $this->returnValue(array(
                array(
                    'indexNumber' => 4711,
                    'header' => '',
                    'description' => ''
                ),
                array(
                    'indexNumber' => 4712,
                    'header' => '',
                    'description' => ''
                )
            ))
        );

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~[^@]4711~', $content);
        $this->assertRegExp('~[^@]4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteHeaderIsPresent()
    {
        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootnotesByUids'),
            array(),
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getFootnotesByUids')->will(
            $this->returnValue(array(
                array(
                    'indexNumber' => 4711,
                    'header' => 'HEADER@4711',
                    'description' => ''
                ),
                array(
                    'indexNumber' => 4712,
                    'header' => 'HEADER@4712',
                    'description' => ''
                )
            ))
        );

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~HEADER@4711~', $content);
        $this->assertRegExp('~HEADER@4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteDescriptionIsPresent()
    {
        $footnoteRepository = $this->getMock(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository',
            array('getFootnotesByUids'),
            array(),
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getFootnotesByUids')->will(
            $this->returnValue(array(
                array(
                    'indexNumber' => 4711,
                    'header' => 'HEADER@4711',
                    'description' => 'DESCRIPTION@4711'
                ),
                array(
                    'indexNumber' => 4712,
                    'header' => 'HEADER@4712',
                    'description' => 'DESCRIPTION@4712'
                )
            ))
        );

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes(array(4711, 4712));
        $this->assertRegExp('~DESCRIPTION@4711~', $content);
        $this->assertRegExp('~DESCRIPTION@4712~', $content);
    }

    /**
     * @test
     */
    public function shouldNotRenderRichText()
    {
        $renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->assertEquals('', $renderingService->renderRichText(''));
    }

    /**
     * @test
     */
    public function shouldRenderRichText()
    {
        $renderingService = new Tx_HappyFeet_Service_Rendering();
        $this->assertContains('test', $renderingService->renderRichText('test'));
    }

    /**
     * @test
     */
    public function alternativeTemplateIsDefinedButFileDoesntExist()
    {
        $template = 'EXT:happy_feet/Resources/Private/Templates/Rendering/Markup.html';
        $failingTemplate = 'EXT:happy_feet/Resources/Private/Templates/Rendering/TestTemplate.html';

        // define typoscript config
        $GLOBALS['TSFE']->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'] = $failingTemplate;

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertEquals($template, $result);
    }

    /**
     * @test
     */
    public function alternativeTemplateIsDefined()
    {
        $template = 'EXT:happy_feet/Resources/Private/Templates/Rendering/RichText.html';

        // define typoscript config
        $GLOBALS['TSFE']->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'] = $template;

        $this->renderingService = new Tx_HappyFeet_Service_Rendering();
        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertEquals($template, $result);
    }

    /**
     * @param $method string
     * @return string
     */
    private function reflectMethodInRenderingService($method)
    {
        $reflector = new ReflectionClass('Tx_HappyFeet_Service_Rendering');
        $method = $reflector->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($this->renderingService, array());
    }
}
