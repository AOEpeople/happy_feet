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

use AOE\HappyFeet\Service\FCEFootnoteService;
use AOE\HappyFeet\Service\RenderingService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FCEFootnoteServiceTest extends UnitTestCase
{
    protected FCEFootnoteService $service;

    protected RenderingService $renderingService;

    protected function setUp(): void
    {
        $this->renderingService = $this->getMockBuilder(RenderingService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['renderFootnotes'])
            ->getMock();

        $this->service = $this->getMockBuilder(FCEFootnoteService::class)
            ->setConstructorArgs([$this->renderingService])
            ->onlyMethods(['getCObj'])
            ->getMock();
    }

    /**
     * @method FCEFootnoteService:renderItemList
     */
    public function testShouldRenderItemListWithEmptyConf(): void
    {
        $content = $this->service->renderItemList('');
        $this->assertSame('', $content);
    }

    /**
     * @method FCEFootnoteService:renderItemList
     */
    public function testShouldRenderItemListIfNoFootnotesSelected(): void
    {
        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)->onlyMethods(
            ['getCurrentVal']
        )->disableOriginalConstructor()
            ->getMock();

        $cObj
            ->expects($this->once())
            ->method('getCurrentVal')
            ->willReturn('');

        $this->service
            ->expects($this->once())
            ->method('getCObj')
            ->willReturn($cObj);

        $this->assertSame('', $this->service->renderItemList('', ['userFunc' => '', 'field' => '']));
    }

    public function testShouldRenderItemLists(): void
    {
        $this->renderingService
            ->method('renderFootnotes')
            ->with(['1', '2'])
            ->willReturn('contentString');

        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)->onlyMethods(
            ['getCurrentVal']
        )->disableOriginalConstructor()
            ->getMock();

        $cObj
            ->expects($this->once())
            ->method('getCurrentVal')
            ->willReturn('1,2');

        $service = new FCEFootnoteService($this->renderingService);
        $service->setCObj($cObj);

        $conf = ['userFunc' => '', 'field' => ''];

        $this->assertSame('contentString', $service->renderItemList('', $conf));
    }
}
