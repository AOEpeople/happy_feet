<?php
namespace AOE\HappyFeet\Tests\Functional\Service;

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

use AOE\HappyFeet\Domain\Model\Footnote;
use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use AOE\HappyFeet\Service\RenderingService;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use ReflectionClass;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * @package HappyFeet
 * @subpackage Service_Test
 */
class RenderingServiceTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/happy_feet'
    ];

    /**
     * @var RenderingService
     */
    protected $renderingService;

    /**
     * Set up test case
     */
    public function setUp(): void
    {
        parent::setUp();
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object'] = [
            'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
            'options' => []
        ];

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template'] = [
            'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
            'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend',
            'groups' => ['system']
        ];

        $footnote1 = $this->getMockBuilder(Footnote::class)->setMethods(['getHeader', 'getDescription', 'getIndexNumber'])->getMock();

        $footnote1->_setProperty('uid', 4711);
        $footnote1->expects($this->any())->method('getHeader')->willReturn('HEADER@4711');
        $footnote1->method('getIndexNumber')->willReturn('4711');
        $footnote1->expects($this->any())->method('getDescription')->willReturn('DESCRIPTION@4711');

        $footnote2 = $this->getMockBuilder(Footnote::class)->setMethods(['getHeader', 'getDescription', 'getIndexNumber'])->getMock();

        $footnote2->_setProperty('uid', 4712);
        $footnote2->method('getHeader')->willReturn('HEADER@4712');
        $footnote2->method('getIndexNumber')->willReturn('4712');
        $footnote2->method('getDescription')->willReturn('DESCRIPTION@4712');

        $footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)->setMethods(['getFootnotesByUids'])->disableOriginalConstructor()->getMock();

        $footnoteRepository->method('getFootnotesByUids')->willReturn([$footnote1, $footnote2]);

        $this->renderingService = GeneralUtility::makeInstance(ObjectManager::class)->get(RenderingService::class);
        $this->renderingService->setFootnoteRepository($footnoteRepository);
    }

    /**
     * @test
     */
    public function shouldNotRenderWithNoUids()
    {
        $content = $this->renderingService->renderFootnotes([]);
        $this->assertEquals('', $content);
    }

    /**
     * @test
     */
    public function shouldNotRenderWhenNoFootnotesAvailable()
    {
        $footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->setMethods(['getFootnotesByUids'])
            ->disableOriginalConstructor()
            ->getMock();

        $footnoteRepository->method('getFootnotesByUids')->willReturn([]);

        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertEquals('', $content);
    }

    /**
     * @test
     */
    public function footnoteIdIsPresent()
    {
        $footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->setMethods(['getFootnotesByUids'])
            ->disableOriginalConstructor()
            ->getMock();

        $footnoteRepository->method('getFootnotesByUids')->willReturn(
            [
                [
                    'indexNumber' => 4711,
                    'header' => '',
                    'description' => ''
                ],
                [
                    'indexNumber' => 4712,
                    'header' => '',
                    'description' => ''
                ]
            ]
        );

        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes([4711, 4712]);

        $this->assertRegExp('~[^@]4711~', $content);
        $this->assertRegExp('~[^@]4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteHeaderIsPresent()
    {
        $footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->setMethods(['getFootnotesByUids'])
            ->disableOriginalConstructor()
            ->getMock();

        $footnoteRepository->method('getFootnotesByUids')->willReturn(
            [
                [
                    'indexNumber' => 4711,
                    'header' => 'HEADER@4711',
                    'description' => ''
                ],
                [
                    'indexNumber' => 4712,
                    'header' => 'HEADER@4712',
                    'description' => ''
                ]
            ]
        );

        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertRegExp('~HEADER@4711~', $content);
        $this->assertRegExp('~HEADER@4712~', $content);
    }

    /**
     * @test
     */
    public function footnoteDescriptionIsPresent()
    {
        $footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->setMethods(['getFootnotesByUids'])
            ->disableOriginalConstructor()
            ->getMock();

        $footnoteRepository->method('getFootnotesByUids')->willReturn(
            [
                [
                    'indexNumber' => 4711,
                    'header' => 'HEADER@4711',
                    'description' => 'DESCRIPTION@4711'
                ],
                [
                    'indexNumber' => 4712,
                    'header' => 'HEADER@4712',
                    'description' => 'DESCRIPTION@4712'
                ]
            ]
        );

        $this->renderingService->setFootnoteRepository($footnoteRepository);

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertRegExp('~DESCRIPTION@4711~', $content);
        $this->assertRegExp('~DESCRIPTION@4712~', $content);
    }

    /**
     * @test
     */
    public function shouldNotRenderRichText()
    {
        $this->assertEquals('', $this->renderingService->renderRichText(''));
    }

    /**
     * @test
     */
    public function shouldRenderRichText()
    {
        $this->assertContains('test', $this->renderingService->renderRichText('test'));
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

        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertEquals($template, $result);
    }

    /**
     * @test
     */
    public function alternativeTemplateIsDefined()
    {
        $template = 'EXT:happy_feet/Resources/Private/Templates/Rendering/Markup.html';

        // define typoscript config
        $GLOBALS['TSFE']->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'] = $template;

        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertEquals($template, $result);
    }

    /**
     * @param $method string
     * @return string
     * @throws \ReflectionException
     */
    private function reflectMethodInRenderingService($method)
    {
        $reflector = new ReflectionClass(RenderingService::class);
        $method = $reflector->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($this->renderingService, []);
    }
}
