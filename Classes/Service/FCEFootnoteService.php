<?php

namespace AOE\HappyFeet\Service;

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

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use UnexpectedValueException;

/**
 * Render Footnotes for FCE
 *
 * @package HappyFeet
 * @subpackage Service
 */
class FCEFootnoteService
{
    public ?ContentObjectRenderer $cObj = null;

    private RenderingService $renderingService;

    public function __construct(RenderingService $renderingService)
    {
        $this->renderingService = $renderingService;
    }

    /**
     * @param array $conf optional (this will be automatically set, of this method is called via 'TYPOSCRIPT-userFunc')
     * @return ?string The wrapped index value
     * @throws UnexpectedValueException
     */
    public function renderItemList(string $content, array $conf = []): ?string
    {
        if (!array_key_exists('userFunc', $conf) || !array_key_exists('field', $conf)) {
            return '';
        }

        if (array_key_exists('isGridElement', $conf) && (bool) $conf['isGridElement']) {
            $footnoteUids = $this->getCObj()
                ->data['pi_flexform']['data']['sDEF']['lDEF'][$conf['field']]['vDEF'];
        } else {
            $footnoteUids = $this->getCObj()
                ->getCurrentVal();
        }

        if (empty($footnoteUids)) {
            return '';
        }

        return $this->renderingService->renderFootnotes(explode(',', $footnoteUids));
    }

    public function setCObj(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }

    protected function getCObj(): ContentObjectRenderer
    {
        return $this->cObj;
    }
}
