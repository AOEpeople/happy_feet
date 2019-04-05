<?php
namespace Aoe\HappyFeet\Typo3\Service\v62;

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

use AOE\Happyfeet\Service\AbstractService;
use AOE\Happyfeet\Service\Rendering;

/**
 * @package HappyFeet
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class LinkHandler extends AbstractService
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
     * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $pObj
     * @return string
     */
    public function main($linktxt, $typoLinkConfiguration, $linkHandlerKeyword, $linkHandlerValue, $linkParams, $pObj)
    {
        if ($linkHandlerKeyword === self::KEYWORD) {
            $footnoteHtml = $this->getRenderingService()->renderFootnotes($this->getFootnoteIds($linkHandlerValue));
            // Trim HTML-code of footnotes - Otherwise some ugly problems can occur
            // (e.g. TYPO3 renders p-tags around the HTML-code)
            return $linktxt . trim($footnoteHtml);
        }
        return $linktxt;
    }

    /**
     * @return Rendering
     */
    protected function getRenderingService()
    {
        /** @var Rendering $renderingService */
        $renderingService = $this->getObjectManager()->get(Rendering::class);
        return $renderingService;
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
