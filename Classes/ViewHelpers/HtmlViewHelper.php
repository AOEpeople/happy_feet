<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 AOE GmbH <dev@aoe.com>
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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Fluid\ViewHelpers\Format\HtmlViewHelper;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Override fluid-HtmlViewHelper:
 * In some cases, we don't want to simulate TSFE-object, if TYPO3_MODE is 'BE'. So this
 * viewHelper supports a configuration to disable TSFE-simulation in BE.
 *
 * @package HappyFeet
 * @subpackage Service_Test
 */
class Tx_HappyFeet_ViewHelpers_HtmlViewHelper extends HtmlViewHelper
{
    /**
     * @param string $parseFuncTSPath path to TypoScript parseFunc setup.
     * @param boolean $simulateTSFEinBackend
     * @return string the parsed string.
     */
    public function render($parseFuncTSPath = 'lib.parseFunc_RTE', $simulateTSFEinBackend = false)
    {
        if (TYPO3_MODE === 'BE' && $simulateTSFEinBackend === true) {
            self::simulateFrontendEnvironment();
        }
        $value = $this->renderChildren();
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $content = $contentObject->parseFunc($value, array(), '< ' . $parseFuncTSPath);
        if (TYPO3_MODE === 'BE' && $simulateTSFEinBackend === true) {
            self::resetFrontendEnvironment();
        }
        return $content;
    }
}
