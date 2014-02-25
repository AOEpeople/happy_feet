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
 * @subpackage Domain_Repository
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class Tx_HappyFeet_Domain_Repository_FootnoteRepository extends Tx_Extbase_Persistence_Repository
{

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings Tx_Extbase_Persistence_Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get( 'Tx_Extbase_Persistence_Typo3QuerySettings' );
        $defaultQuerySettings->setRespectStoragePage( false );
        $defaultQuerySettings->setRespectSysLanguage( false );
        $defaultQuerySettings->setRespectEnableFields( false );
        $this->setDefaultQuerySettings( $defaultQuerySettings );

    }

    /**
     * Returns the smallest index which is not used.
     *
     * @return integer
     */
    public function getLowestFreeIndex()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult( true );
        $query->statement( 'SELECT index_number from ' . strtolower( $this->objectType ) . ' WHERE deleted=0' );
        $index = 1;
        $results = $query->execute();
        if (false === is_array( $results ) || sizeof( $results ) < 1) {
            return $index;
        }
        $indexes = array();
        foreach ($results as $result) {
            $indexes[] = (integer) $result['index_number'];
        }
        for ($index = 1; $index <= sizeof( $indexes ) + 1; $index++) {
            if (false === in_array( $index, $indexes )) {
                break;
            }
        }
        return $index;
    }

    /**
     * @param Tx_HappyFeet_Domain_Model_Footnote $object
     * @throws Tx_Extbase_Persistence_Exception_IllegalObjectType
     * @return void
     */
    public function add($object)
    {
        /** @var Tx_HappyFeet_Domain_Model_Footnote $object */
        if (false === ( $object instanceof Tx_HappyFeet_Domain_Model_Footnote)) {
            throw new Tx_Extbase_Persistence_Exception_IllegalObjectType(
                'The object given to add() was not of the type (' . $this->objectType . ') this repository manages.',
                1392911702
            );
        }
        $object->setIndexNumber( $this->getLowestFreeIndex() );
        parent::add( $object );
    }
	/**
	 * @param array $uIds
	 * @return array|Tx_Extbase_Persistence_QueryResultInterface
	 */
	public function getFootNoteByIds(array $uIds) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage( false );
		$query->getQuerySettings()->setRespectSysLanguage( false );
		$query->getQuerySettings()->setRespectEnableFields( true );
		$query->matching($query->in('uid', $uIds));

		return $query->execute();
	}
}