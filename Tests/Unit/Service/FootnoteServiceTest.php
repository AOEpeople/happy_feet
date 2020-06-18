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

use AOE\HappyFeet\Domain\Model\Footnote;
use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use AOE\HappyFeet\Service\FootnoteService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * @package HappyFeet
 * @subpackage Service_Test
 */
class FootnoteServiceTest extends UnitTestCase
{
    /**
     * @var FootnoteRepository
     */
    private $footnoteRepository;

    /**
     * @var FootnoteService
     */
    private $footnoteService;

    protected function setUp()
    {
        $this->footnoteRepository = $this
            ->getMockBuilder(FootnoteRepository::class)
            ->setMethods(['getFootnoteByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->footnoteService = new FootnoteService($this->footnoteRepository);
    }

    /**
     * @test
     */
    public function shouldGetFootnoteById()
    {
        $footnote = new Footnote();

        $this->footnoteRepository
            ->method('getFootnoteByUid')
            ->with(123)
            ->willReturn($footnote);

        $this->assertSame($footnote, $this->footnoteService->getFootnoteById(123));
    }

    /**
     * @test
     */
    public function shouldReturnNullIfFootnoteNotFound()
    {
        $this->assertNull($this->footnoteService->getFootnoteById(456));
    }
}
