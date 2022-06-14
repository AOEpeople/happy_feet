<?php
namespace AOE\HappyFeet\Tests\Unit\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 AOE GmbH <dev@aoe.com>
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

use AOE\HappyFeet\Service\RenderingService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * @package HappyFeet
 * @subpackage Service_Test
 */
class RenderingServiceTest extends UnitTestCase
{
    /**
     * @var RenderingService|MockObject
     */
    private $renderingService;

    /**
     * @var ContentObjectRenderer|MockObject
     */
    private $contentObjectRenderer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->onlyMethods(['parseFunc'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->renderingService = $this->getMockBuilder(RenderingService::class)
            ->onlyMethods(['getContentObjectRenderer'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->renderingService->expects(self::any())->method('getContentObjectRenderer')
            ->willReturn($this->contentObjectRenderer);
    }

    /**
     * @test
     */
    public function renderRichTextWithEmptyText()
    {
        $this->contentObjectRenderer->expects(self::never())->method('parseFunc');
        $this->renderingService->renderRichText('');
    }

    /**
     * @test
     */
    public function renderRichTextWithDefaultParams()
    {
        $this->contentObjectRenderer->expects(self::once())->method('parseFunc')
            ->with('text', [], '< lib.parseFunc_HappyFeet');

        $this->renderingService->renderRichText('text');
    }

    /**
     * @test
     */
    public function renderRichTextWithSpecificParams()
    {
        $this->contentObjectRenderer->expects(self::once())->method('parseFunc')
            ->with('special text', [], '< lib.parseFunc_HappyFeet_custom');

        $this->renderingService->renderRichText('special text', [], '< lib.parseFunc_HappyFeet_custom');
    }
}
