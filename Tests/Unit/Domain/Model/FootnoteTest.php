<?php

namespace AOE\HappyFeet\Tests\Unit\Domain\Model;

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

use AOE\HappyFeet\Domain\Model\Footnote;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @package HappyFeet
 * @subpackage Domain_Model_Test
 */
class FootnoteTest extends UnitTestCase
{
    protected Footnote $footnote;

    protected function setUp(): void
    {
        $this->footnote = new Footnote();
    }

    public function testShouldSetTitle(): void
    {
        $this->footnote->setTitle('Dummy title');
        $this->assertSame('Dummy title', $this->footnote->getTitle());
    }

    public function testShouldSetDescription(): void
    {
        $this->footnote->setDescription('Dummy Description');
        $this->assertSame('Dummy Description', $this->footnote->getDescription());
    }

    public function testShouldSetHeader(): void
    {
        $this->footnote->setHeader('Dummy Header');
        $this->assertSame('Dummy Header', $this->footnote->getHeader());
    }

    public function testShouldSetIndexNumber(): void
    {
        $this->footnote->setIndexNumber(123);
        $this->assertSame(123, $this->footnote->getIndexNumber());
    }
}
