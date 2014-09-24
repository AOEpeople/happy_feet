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
 * @author Torsten Zander <torsten.zander@aoe.com>
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Service_FCEFootnoteServiceTest extends Tx_Phpunit_TestCase
{

    /**
     * @var Tx_HappyFeet_Service_FCEFootnoteService
     */
    private $service;

    /**
     * Tx_HappyFeet_Domain_Repository_FootnoteRepository
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * setup
     */
    public function setUp()
    {
        $this->service = $this->getMock('Tx_HappyFeet_Service_FCEFootnoteService', array('getCObj'));
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
     * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
     */
    public function shouldThrowExceptionIfCObjNotExists()
    {
        $service = new Tx_HappyFeet_Service_FCEFootnoteService();
        $service->renderItemList('', array('userFunc' => '', 'field' => ''));
    }

    /**
     * @test
     * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemListIfNoFootnotesSelected()
    {
        $cObj = $this->getMock('tslib_cObj', array('getCurrentVal'), array(), '', false);
        $cObj->expects($this->once())->method('getCurrentVal')->will($this->returnValue(''));
        $this->service->expects($this->once())->method('getCObj')->will($this->returnValue($cObj));
        $this->assertEquals('', $this->service->renderItemList('', array('userFunc' => '', 'field' => '')));
    }

    /**
     * @test
     * @method Tx_HappyFeet_Service_FCEFootnoteService:renderItemList
     */
    public function shouldRenderItemList()
    {
        $renderer = $this->getMock('Tx_HappyFeet_Service_Rendering', array('renderFootnotes'));
        $service = $this->getMock('Tx_HappyFeet_Service_FCEFootnoteService', array('getCObj', 'getRenderingService'));
        $cObj = $this->getMock('tslib_cObj', array('getCurrentVal'), array(), '', false);
        $renderer->expects($this->any())->method('renderFootnotes')
            ->with(array(1, 2))
            ->will(
                $this->returnValue('contentString')
            );
        $cObj->expects($this->once())->method('getCurrentVal')->will($this->returnValue('1,2'));
        $service->expects($this->once())->method('getCObj')->will($this->returnValue($cObj));
        $service->expects($this->once())->method('getRenderingService')->will($this->returnValue($renderer));
        $conf = array('userFunc' => '', 'field' => '');
        $this->assertEquals('contentString', $service->renderItemList('', $conf));
    }
}