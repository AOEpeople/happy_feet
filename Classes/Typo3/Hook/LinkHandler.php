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

use InvalidArgumentException;
use TYPO3\CMS\Core\LinkHandling\LinkHandlingInterface;

/**
 * Linkhandler hook to manipulate link data before it is processed by core typolink method.
 *
 * @package HappyFeet
 */
class LinkHandler implements LinkHandlingInterface
{
    /**
     * The Base URN for this link handling to act on
     */
    protected string $baseUrn = 't3://happy_feet';

    /**
     * Returns all valid parameters for linking to a TYPO3 page as a string
     *
     * @throws InvalidArgumentException
     */
    public function asString(array $parameters): string
    {
        if (empty($parameters['uid'])) {
            throw new \InvalidArgumentException('The HappyFeetLinkHandler expects uid as $parameter configuration.', 1486155150);
        }

        $urn = $this->baseUrn;
        $urn .= sprintf('?uid=%s', $parameters['uid']);

        if (!empty($parameters['fragment'])) {
            $urn .= sprintf('#%s', $parameters['fragment']);
        }

        return $urn;
    }

    /**
     * Returns all relevant information built in the link to a page (see asString())
     *
     * @throws InvalidArgumentException
     */
    public function resolveHandlerData(array $data): array
    {
        if (empty($data['uid'])) {
            throw new InvalidArgumentException('The HappyFeetLinkHandler expects identifier, uid as $data configuration', 1486155151);
        }

        return $data;
    }
}
