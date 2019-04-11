<?php
namespace AOE\HappyFeet\Domain\Repository;

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
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Footnote objects.
 *
 * @package HappyFeet
 * @subpackage Domain_Repository
 * @author Timo Fuchs <timo.fuchs@aoe.com>
 */
class FootnoteRepository extends Repository
{
    /**
     * @var array
     */
    public static $uids;

    /**
     * @var string
     */
    protected $tableName = 'tx_happyfeet_domain_model_footnote';

    /**
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $defaultQuerySettings->setIgnoreEnableFields(false)->setIncludeDeleted(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * Returns the smallest index which is not used.
     *
     * @return integer
     */
    public function getLowestFreeIndexNumber()
    {
        $query = $this->createQuery();
        $query->statement('SELECT index_number from ' . $this->tableName . ' WHERE deleted=0');
        $index = 1;
        $results = $query->execute(true);
        if (false === is_array($results) || sizeof($results) < 1) {
            return $index;
        }
        $indexes = array();
        foreach ($results as $result) {
            $indexes[] = (integer)$result['index_number'];
        }
        for ($index = 1; $index <= sizeof($indexes) + 1; $index++) {
            if (false === in_array($index, $indexes)) {
                break;
            }
        }
        return $index;
    }

    /**
     * @param Footnote $object
     * @return void
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function add($object)
    {
        /** @var Footnote $object */
        if (false === ($object instanceof Footnote)) {
            throw new \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException(
                'The object given to add() was not of the type (' . $this->objectType . ') this repository manages.',
                1392911702
            );
        }
        $object->setIndexNumber($this->getLowestFreeIndexNumber());
        parent::add($object);
    }

    /**
     * @param array $uids
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function getFootnotesByUids(array $uids)
    {
        self::$uids = $uids;
        $query = $this->createQuery();
        $query->setQuerySettings($this->defaultQuerySettings);
        $query->matching($query->in('uid', $uids));
        return $this->sortFootnotesByUids($query->execute(), $uids);
    }

    /**
     * @param array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface $queryResult
     * @param $uids
     * @return mixed
     */
    public function sortFootnotesByUids($queryResult, $uids)
    {
        if ($queryResult instanceof \TYPO3\CMS\Extbase\Persistence\QueryResultInterface) {
            $queryResult = $queryResult->toArray();
        }
        usort($queryResult, [$this, 'usortFootnotesByUids']);
        return $queryResult;
    }

    /**
     * @param Footnote $a
     * @param Footnote $b
     * @return integer
     */
    public static function usortFootnotesByUids(
        Footnote $a,
        Footnote $b
    )
    {
        $map = array_flip(self::$uids);
        if ($map[$a->getUid()] >= $map[$b->getUid()]) {
            return 1;
        }
        return -1;
    }
}
