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
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_HappyFeet_ViewHelpers_FlatifyViewHelperTest extends tx_phpunit_testcase
{
    /**
     * @var Tx_HappyFeet_ViewHelpers_FlatifyViewHelper
     */
    private $viewHelper;

    /**
     * Set up the test case
     */
    public function setUp()
    {
        $this->viewHelper = new Tx_HappyFeet_ViewHelpers_FlatifyViewHelper();
    }

    /**
     * @test
     */
    public function lineBreaksWillBeRemoved()
    {
        $actualOutput = $this->viewHelper->render( $this->getTemplateFixture() );
        $this->assertNotRegExp(
            "/\r|\n/",
            $actualOutput,
            'Some of the line breaks are not replaced by the view helper!'
        );
    }

    /**
     * @return string
     */
    private function getTemplateFixture()
    {
        $file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'Template.html';
        return file_get_contents( $file );
    }
}