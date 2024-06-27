<?php

namespace AOE\HappyFeet\Typo3\Hook;

/*
 * Copyright notice
 *
 * (c) 2014 AOE GmbH <dev@aoe.com>
 *
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * Class which provides TCE main hooks.
 */
class Tcemain
{
    public function __construct(
        private FootnoteRepository $footnoteRepository
    ) {
    }

    /**
     * @param string $status Operation type e.g new, update, delete.
     * @param string $table Database table on which the operation is performed.
     * @param integer $id Table record ID.
     * @param array $fieldArray Key/value pair of record fields.
     * @param DataHandler $tcemain Parent class instance from which the hook method is called.
     *
     * @codingStandardsIgnore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, DataHandler $tcemain): void
    {
        if ($table !== 'tx_happyfeet_domain_model_footnote') {
            return;
        }

        if ($status === 'new') {
            $fieldArray['index_number'] = $this->footnoteRepository->getLowestFreeIndexNumber();
        }

        if ($status === 'delete') {
            $fieldArray['index_number'] = 0;
        }
    }
}
