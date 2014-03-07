<?php

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

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
class Tx_HappyFeet_Typo3_Service_LinkHandler extends Tx_HappyFeet_Service_Abstract
{
    /**
     * @var string
     */
    const KEYWORD = 'happyfeet';

    /**
     * @param string $linktxt
     * @param array $typoLinkConfiguration TypoLink Configuration array
     * @param string $linkHandlerKeyword Define the identifier that an record is given
     * @param string $linkHandlerValue Table and uid of the requested record like "tx_happyfeet_domain_model_footnote:2"
     * @param string $linkParams Full link params like "footnote:tx_aoefootnote_item:2"
     * @param tslib_cObj $pObj
     * @return string
     */
    public function main($linktxt, $typoLinkConfiguration, $linkHandlerKeyword, $linkHandlerValue, $linkParams, $pObj)
    {
        if ($linkHandlerKeyword === self::KEYWORD) {
            $footnote = $this->getRenderingService()->renderFootnotes($this->getFootnoteIds($linkHandlerValue));
            return $linktxt . $footnote;
        }
        return $linktxt;
    }

    /**
     * @return Tx_HappyFeet_Service_Rendering
     */
    protected function getRenderingService()
    {
        return $this->getObjectManager()->get('Tx_HappyFeet_Service_Rendering');
    }

    /**
     * @param $str
     * @return array
     */
    private function getFootnoteIds($str)
    {
        $parts = explode(':', $str);
        return array($parts[1]);
    }
}