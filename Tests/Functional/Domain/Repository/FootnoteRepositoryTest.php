<?php

namespace AOE\HappyFeet\Tests\Functional\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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
use stdClass;
use Throwable;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FootnoteRepositoryTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/happy_feet',
    ];

    private FootnoteRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = GeneralUtility::makeInstance(FootnoteRepository::class);
        $this->repository->initializeObject();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->repository);
    }

    public function testShouldGetDefaultIndexWhenNoRecordsAvailable(): void
    {
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertSame(1, $lowestIndex);
    }

    public function testShouldGetLowestIndex(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote.csv');
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertSame(1, $lowestIndex);
    }

    public function testShouldGetIndexWithGap(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote_gap.csv');
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertSame(2, $lowestIndex);
    }

    public function testShouldGetNextIndexInRow(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote_row.csv');
        $lowestIndex = $this->repository->getLowestFreeIndexNumber();
        $this->assertSame(3, $lowestIndex);
    }

    public function testShouldGetFootnoteByUid(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote.csv');
        $footnote = $this->repository->getFootnoteByUid(1);
        $this->assertInstanceOf(Footnote::class, $footnote);
        $this->assertSame(1, $footnote->getUid());
    }

    public function testShouldReturnNullIfFootnoteNotFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote.csv');
        $footnote = $this->repository->getFootnoteByUid(99);
        $this->assertNull($footnote);
    }

    public function testShouldGetFootnotesByUids(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote_collection.csv');
        $footnotes = $this->repository->getFootnotesByUids([2, 4]);
        $this->assertCount(2, $footnotes);
        $this->assertSame(2, $footnotes[0]->getUid());
        $this->assertSame(4, $footnotes[1]->getUid());
    }

    public function testShouldSortFootnotesByGivenOrderOfUids(): void
    {
        $this->importCSVDataSet(__DIR__ . '/fixtures/tx_happyfeet_domain_model_footnote_collection.csv');
        $footnotes = $this->repository->getFootnotesByUids([4, 1, 5, 3, 2]);
        $this->assertCount(5, $footnotes);
        $this->assertSame(4, $footnotes[0]->getUid());
        $this->assertSame(1, $footnotes[1]->getUid());
        $this->assertSame(5, $footnotes[2]->getUid());
        $this->assertSame(3, $footnotes[3]->getUid());
        $this->assertSame(2, $footnotes[4]->getUid());
    }

    public function testShouldThrowExceptionWithInvalidObject(): void
    {
        $this->expectException(IllegalObjectTypeException::class);

        $footnote = new stdClass();
        $this->repository->add($footnote);
    }

    /**
     * assert that no exception is thrown
     */
    public function testShouldAddObject(): void
    {
        try {
            $footnote = new Footnote();
            $this->repository->add($footnote);
        } catch (Throwable) {
            $this->fail('assert that no exception is thrown.');
        }

        $this->assertTrue(true);
    }
}
