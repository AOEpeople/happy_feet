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

use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use AOE\HappyFeet\Service\RenderingService;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\NormalizedParams;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RenderingServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/happy_feet',
    ];

    protected RenderingService $renderingService;

    /**
     * @var FootnoteRepository
     */
    protected MockObject $footnoteRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase'] = [
            'backend' => NullBackend::class,
            'options' => [],
        ];

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template'] = [
            'backend' => NullBackend::class,
            'frontend' => PhpFrontend::class,
            'groups' => ['system'],
        ];

        $this->footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->onlyMethods(['getFootnotesByUids'])
            ->disableOriginalConstructor()
            ->getMock();

        $GLOBALS['TYPO3_REQUEST'] = (new ServerRequest('https://www.example.com/'))
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('normalizedParams', new NormalizedParams([], [], '', ''));

        $this->renderingService = GeneralUtility::makeInstance(RenderingService::class, $this->footnoteRepository);
    }

    public function testShouldNotRenderWhenNoFootnotesAvailable(): void
    {
        $this->footnoteRepository
            ->method('getFootnotesByUids')
            ->willReturn([]);

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertSame('', $content);
    }

    public function testFootnoteIdIsPresent(): void
    {
        $this->footnoteRepository
            ->method('getFootnotesByUids')
            ->willReturn(
                [
                    [
                        'indexNumber' => 4711,
                        'header' => '',
                        'description' => '',
                    ],
                    [
                        'indexNumber' => 4712,
                        'header' => '',
                        'description' => '',
                    ],
                ]
            );

        $content = $this->renderingService->renderFootnotes([4711, 4712]);

        $this->assertMatchesRegularExpression('~[^@]4711~', $content);
        $this->assertMatchesRegularExpression('~[^@]4712~', $content);
    }

    public function testFootnoteHeaderIsPresent(): void
    {
        $this->footnoteRepository
            ->method('getFootnotesByUids')
            ->willReturn(
                [
                    [
                        'indexNumber' => 4711,
                        'header' => 'HEADER@4711',
                        'description' => '',
                    ],
                    [
                        'indexNumber' => 4712,
                        'header' => 'HEADER@4712',
                        'description' => '',
                    ],
                ]
            );

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertMatchesRegularExpression('~HEADER@4711~', $content);
        $this->assertMatchesRegularExpression('~HEADER@4712~', $content);
    }

    public function testFootnoteDescriptionIsPresent(): void
    {
        $this->footnoteRepository
            ->method('getFootnotesByUids')
            ->willReturn(
                [
                    [
                        'indexNumber' => 4711,
                        'header' => 'HEADER@4711',
                        'description' => 'DESCRIPTION@4711',
                    ],
                    [
                        'indexNumber' => 4712,
                        'header' => 'HEADER@4712',
                        'description' => 'DESCRIPTION@4712',
                    ],
                ]
            );

        $content = $this->renderingService->renderFootnotes([4711, 4712]);
        $this->assertMatchesRegularExpression('~DESCRIPTION@4711~', $content);
        $this->assertMatchesRegularExpression('~DESCRIPTION@4712~', $content);
    }

    public function testShouldNotRenderRichText(): void
    {
        $this->assertSame('', $this->renderingService->renderRichText(''));
    }

    public function testShouldRenderRichText(): void
    {
        $this->assertStringContainsString('test', $this->renderingService->renderRichText('test', ['htmlSanitize' => 1]));
    }

    public function testAlternativeTemplateIsDefinedButFileDoesntExist(): void
    {
        $template = 'EXT:happy_feet/Resources/Private/Templates/Rendering/Markup.html';
        $failingTemplate = 'EXT:happy_feet/Resources/Private/Templates/Rendering/TestTemplate.html';

        // define typoscript config
        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $GLOBALS['TSFE']->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'] = $failingTemplate;

        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertSame($template, $result);
    }

    public function testAlternativeTemplateIsDefined(): void
    {
        $template = 'EXT:happy_feet/Resources/Private/Templates/Rendering/Markup.html';

        // define typoscript config
        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);
        $GLOBALS['TSFE']->tmpl = new \stdClass();
        $GLOBALS['TSFE']->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'] = $template;

        $result = $this->reflectMethodInRenderingService('getTemplatePath');

        $this->assertSame($template, $result);
    }

    private function reflectMethodInRenderingService(string $method): string
    {
        $reflector = new ReflectionClass(RenderingService::class);
        $method = $reflector->getMethod($method);
        $method->setAccessible(true);
        return $method->invokeArgs($this->renderingService, []);
    }
}
