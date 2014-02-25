<?php

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
        if ($status === 'new' && $table == 'tx_happyfeet_domain_model_footnote') {
            $fieldArray['index_number'] = $this->getFootnoteRepository()->getLowestFreeIndex();
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