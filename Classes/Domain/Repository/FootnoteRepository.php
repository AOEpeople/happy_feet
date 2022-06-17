<?php

namespace AOE\HappyFeet\Domain\Repository;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2020 AOE GmbH <dev@aoe.com>
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
use Doctrine\DBAL\Query\QueryBuilder;
use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Footnote objects.
 *
 * @package HappyFeet
 * @subpackage Domain_Repository
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


    public function initializeObject()
    {
        /** @var Typo3QuerySettings $defaultQuerySettings */
        $defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $defaultQuerySettings->setRespectStoragePage(false);
        $defaultQuerySettings->setRespectSysLanguage(false);
        $defaultQuerySettings->setIgnoreEnableFields(false)
            ->setIncludeDeleted(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * Returns the smallest index which is not used.
     *
     * @return integer
     */
    public function getLowestFreeIndexNumber()
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);

        $results = $queryBuilder
            ->select('index_number')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()
                    ->eq('deleted', $queryBuilder->createNamedParameter(0, PDO::PARAM_INT))
            )
            ->execute()
            ->fetchAllAssociative();

        $index = 1;
        if (count($results) < 1) {
            return $index;
        }
        $indexes = [];
        foreach ($results as $result) {
            $indexes[] = (int) $result['index_number'];
        }
        for ($index = 1; $index <= count($indexes) + 1; $index++) {
            if (in_array($index, $indexes, true) === false) {
                break;
            }
        }
        return $index;
    }

    /**
     * @param Footnote $object
     * @throws IllegalObjectTypeException
     */
    public function add($object)
    {
        /** @var Footnote $object */
        if (false === ($object instanceof Footnote)) {
            throw new IllegalObjectTypeException(
                'The object given to add() was not of the type (' . $this->objectType . ') this repository manages.',
                1392911702
            );
        }
        $object->setIndexNumber($this->getLowestFreeIndexNumber());
        parent::add($object);
    }

    /**
     * @param integer $uid
     * @return Footnote|null
     */
    public function getFootnoteByUid($uid)
    {
        $query = $this->createQuery();
        $query->setQuerySettings($this->defaultQuerySettings);

        return $query->matching($query->equals('uid', $uid))
            ->execute()
            ->getFirst();
    }

    /**
     * @param array $uids
     * @return array|QueryResultInterface
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
     * @param array|QueryResultInterface $queryResult
     * @param $uids
     * @return mixed
     */
    public function sortFootnotesByUids($queryResult, $uids)
    {
        if ($queryResult instanceof QueryResultInterface) {
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
    ) {
        $map = array_flip(self::$uids);
        if ($map[$a->getUid()] >= $map[$b->getUid()]) {
            return 1;
        }
        return -1;
    }
}
