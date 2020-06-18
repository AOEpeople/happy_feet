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
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
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
     * setup
     */
    public function setUp()
    {
        $this->service = $this->getMockBuilder(FCEFootnoteService::class)
            ->setMethods(['getCObj'])
            ->getMock();
    }

    /**
     * @test
     * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemListWithEmptyConf()
    {
        $content = $this->service->renderItemList('');
        $this->assertEquals('', $content);
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     * @method FCEFootnoteService:renderItemList
     */
    public function shouldThrowExceptionIfCObjNotExists()
    {
        $service = new FCEFootnoteService();
        $service->renderItemList('', array('userFunc' => '', 'field' => ''));
    }

    /**
     * @test
     * @method FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemListIfNoFootnotesSelected()
    {
        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)
            ->setMethods(['getCurrentVal'])
            ->disableOriginalConstructor()
            ->getMock();

        $cObj->expects($this->once())->method('getCurrentVal')->willReturn('');
        $this->service->expects($this->once())->method('getCObj')->willReturn($cObj);

        $this->assertEquals('', $this->service->renderItemList('', array('userFunc' => '', 'field' => '')));
    }

    /**
     * @test
     */
    public function shouldRenderItemLists()
    {
        $renderer = $this->getMockBuilder(RenderingService::class)->setMethods(['renderFootnotes'])->getMock();
        $renderer->method('renderFootnotes')->with([1, 2])->willReturn('contentString');

        $cObj = $this->getMockBuilder(ContentObjectRenderer::class)->setMethods(['getCurrentVal'])->disableOriginalConstructor()->getMock();
        $cObj->expects($this->once())->method('getCurrentVal')->willReturn('1,2');

        $service = new FCEFootnoteService();
        $service->injectRenderingService($renderer);
        $service->setCObj($cObj);

        $conf = ['userFunc' => '', 'field' => ''];

        $this->assertEquals('contentString', $service->renderItemList('', $conf));
    }
}
