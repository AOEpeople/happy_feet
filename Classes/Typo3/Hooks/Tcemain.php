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
 * Class which provides TCE main hooks.
 *
 * @package HappyFeet
 * @subpackage Typo3_Hooks
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Typo3_Hooks_Tcemain
{
    /**
     * @param string $status
     * @param string $table
     * @param integer $id
     * @param array $fieldArray
     * @param mixed $tcemain
     * @return void
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, $tcemain)
    {
        if ($table !== 'tx_happyfeet_domain_model_footnote') {
            return;
        }
        if ($status === 'new') {
            $fieldArray['index_number'] = $this->getFootnoteRepository()->getLowestFreeIndexNumber();
        }
        if ($status === 'delete') {
            $fieldArray['index_number'] = 0;
        }
    }

    /**
     * @return Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    protected function getFootnoteRepository()
    {
        return t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager')->get(
            'Tx_HappyFeet_Domain_Repository_FootnoteRepository'
        );
    }
}