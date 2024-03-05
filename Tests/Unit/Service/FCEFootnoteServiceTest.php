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
use UnexpectedValueException;


/**
 * @package HappyFeet
 * @subpackage Service_Test
 */
class FCEFootnoteServiceTest extends UnitTestCase
{
    /**
     * @var FCEFootnoteService
     */
    protected $service;

    /**
     * @var RenderingService
     */
    protected $renderingService;

    /**
     * setup
     */
    public function setUp(): void
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
     * @test
     * @method FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemListWithEmptyConf()
    {
        $content = $this->service->renderItemList('');
        self::assertEquals('', $content);
    }

    /**
     * @test
     * @method FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemListIfNoFootnotesSelected()
    {
        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)->onlyMethods(['getCurrentVal'])->disableOriginalConstructor()->getMock();
        $cObj->expects(self::once())->method('getCurrentVal')->willReturn('');

        $this->service->expects(self::once())->method('getCObj')->willReturn($cObj);

        self::assertEquals('', $this->service->renderItemList('', ['userFunc' => '', 'field' => '']));
    }

    /**
     * @test
     */
    public function shouldRenderItemLists()
    {
        $this->renderingService->method('renderFootnotes')->with(['1', '2'])->willReturn('contentString');

        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)->onlyMethods(['getCurrentVal'])->disableOriginalConstructor()->getMock();
        $cObj->expects(self::once())->method('getCurrentVal')->willReturn('1,2');

        $service = new FCEFootnoteService($this->renderingService);
        $service->setCObj($cObj);

        $conf = ['userFunc' => '', 'field' => ''];

        self::assertEquals('contentString', $service->renderItemList('', $conf));
    }
}
