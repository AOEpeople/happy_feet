<?php
namespace AOE\Happyfeet\Tests\Unit\Typo3\Hooks;

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
 * Tests for class Tx_HappyFeet_Typo3_Hooks_Tcemain.
 *
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */

use AOE\Happyfeet\Domain\Model\Footnote;
use AOE\Happyfeet\Domain\Repository\FootnoteRepository;
use Aoe\HappyFeet\Typo3\Hook\Tcemain;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class TcemainTest extends UnitTestCase
{
    /**
     * @var Tcemain
     */
    private $tcemainHook;

    /**
     * @return void
     */
    public function setUp()
    {
        $footnoteRepository = $this->getMock(
            FootnoteRepository::class,
            ['getLowestFreeIndexNumber'],
            [],
            '',
            false
        );
        $footnoteRepository->expects($this->any())->method('getLowestFreeIndexNumber')->will($this->returnValue(1));
        $this->tcemainHook = $this->getMock('Aoe\HappyFeet\Typo3\Hook\Tcemain', array('getFootnoteRepository'));
        $this->tcemainHook->expects($this->any())->method('getFootnoteRepository')->will(
            $this->returnValue($footnoteRepository)
        );
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithNewFootnote()
    {
        $fieldArray = [];
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfeet_domain_model_footnote',
            null,
            $fieldArray,
            $this->getMock('TYPO3\CMS\Core\DataHandling\DataHandler')
        );
        $this->assertArrayHasKey('index_number', $fieldArray);
        $this->assertEquals(1, $fieldArray['index_number']);
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithExistingFootnote()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'foo',
            Footnote::class,
            null,
            $fieldArray,
            $this->getMock('TYPO3\CMS\Core\DataHandling\DataHandler')
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }

    /**
     * @test
     */
    public function postProcessFieldArrayWithOtherTable()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfoo_domain_model_baz',
            null,
            $fieldArray,
            $this->getMock('TYPO3\CMS\Core\DataHandling\DataHandler')
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }

    /**
     * @test
     */
    public function shouldResetIndexNumber()
    {
        $fieldArray = array();
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'delete',
            Footnote::class,
            null,
            $fieldArray,
            $this->getMock('TYPO3\CMS\Core\DataHandling\DataHandler')
        );
        $this->assertEquals(0, $fieldArray['index_number']);
    }
}
