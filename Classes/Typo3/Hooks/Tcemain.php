<?php

class Tx_HappyFeet_Typo3_Hooks_Tcemain
{

    public function processDatamap_postProcessFieldArray($status, $table, $id, &$fieldArray, $tcemain)
    {
        if ($status === 'new' && $table == 'tx_happyfeet_domain_model_footnote') {
            /** @var Tx_Extbase_Object_ObjectManager $objectManager */
            $objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
            /** @var Tx_HappyFeet_Domain_Repository_FootnoteRepository $footnoteRepository */
            $footnoteRepository = $objectManager->get('Tx_HappyFeet_Domain_Repository_FootnoteRepository');
            $fieldArray['index_number'] = $footnoteRepository->getLowestFreeIndex();
        }
    }
}