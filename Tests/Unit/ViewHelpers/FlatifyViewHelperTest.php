<?php

namespace AOE\HappyFeet\Tests\Unit\ViewHelpers;

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

use AOE\HappyFeet\ViewHelpers\FlatifyViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FlatifyViewHelperTest extends UnitTestCase
{
    /**
     * @var FlatifyViewHelper
     */
    protected $viewHelper;

    /**
     * Set up the test case
     */
    protected function setUp(): void
    {
        $this->viewHelper = new FlatifyViewHelper();
    }

    /**
     * @dataProvider getTemplateFixtureProvider
     */
    public function testLineBreaksWillBeRemoved(string $fixture): void
    {
        $actualOutput = $this->viewHelper->render($fixture);
        $this->assertSame('such a beautiful footnote', $actualOutput);
    }

    public function getTemplateFixtureProvider(): array
    {
        return [
            'windows' => ["\r\nsuch a beautiful footnote\r\n"],
            'unix' => ["\nsuch a beautiful footnote\n"],
            'mac' => ["\rsuch a beautiful footnote\r"],
        ];
    }
}
