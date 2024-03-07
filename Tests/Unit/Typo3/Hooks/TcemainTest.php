<?php

namespace AOE\HappyFeet\Tests\Unit\Typo3\Hooks;

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
 */

use AOE\HappyFeet\Domain\Model\Footnote;
use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use AOE\HappyFeet\Typo3\Hook\Tcemain;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TcemainTest extends UnitTestCase
{
    /**
     * @var Tcemain
     */
    protected $tcemainHook;

    /**
     * @var DataHandler
     */
    protected $dataHandler;

    /**
     * @var FootnoteRepository|MockObject
     */
    protected $footnoteRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->footnoteRepository = $this->getMockBuilder(FootnoteRepository::class)
            ->onlyMethods(['getLowestFreeIndexNumber'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->tcemainHook = new Tcemain($this->footnoteRepository);

        $this->dataHandler = $this->getMockBuilder(DataHandler::class)->disableOriginalConstructor()->getMock();
    }

    public function testPostProcessFieldArrayWithNewFootnote(): void
    {
        $this->footnoteRepository
            ->expects($this->once())
            ->method('getLowestFreeIndexNumber')
            ->willReturn(1);

        $fieldArray = [];
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfeet_domain_model_footnote',
            null,
            $fieldArray,
            $this->dataHandler
        );

        $this->assertArrayHasKey('index_number', $fieldArray);
        $this->assertSame(1, $fieldArray['index_number']);
    }

    public function testPostProcessFieldArrayWithExistingFootnote(): void
    {
        $this->footnoteRepository
            ->expects($this->atMost(1))
            ->method('getLowestFreeIndexNumber')
            ->willReturn(1);

        $fieldArray = [];
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'foo',
            Footnote::class,
            null,
            $fieldArray,
            $this->dataHandler
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }

    public function testPostProcessFieldArrayWithOtherTable(): void
    {
        $this->footnoteRepository
            ->expects($this->atMost(1))
            ->method('getLowestFreeIndexNumber')
            ->willReturn(1);

        $fieldArray = [];
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'new',
            'tx_happyfoo_domain_model_baz',
            null,
            $fieldArray,
            $this->dataHandler
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }

    public function testShouldResetIndexNumber(): void
    {
        $this->footnoteRepository
            ->expects($this->atMost(1))
            ->method('getLowestFreeIndexNumber')
            ->willReturn(1);

        $fieldArray = [];
        $this->tcemainHook->processDatamap_postProcessFieldArray(
            'delete',
            Footnote::class,
            null,
            $fieldArray,
            $this->dataHandler
        );
        $this->assertArrayNotHasKey('index_number', $fieldArray);
    }
}
