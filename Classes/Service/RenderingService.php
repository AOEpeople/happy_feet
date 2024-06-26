<?php

namespace AOE\HappyFeet\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 AOE GmbH <dev@aoe.com>
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
use AOE\HappyFeet\Domain\Repository\FootnoteRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class RenderingService implements SingletonInterface
{
    /**
     * @var string
     */
    private const DEFAULT_TEMPLATE = 'Markup';

    private ?ContentObjectRenderer $contentObjectRenderer = null;

    public function __construct(
        private FootnoteRepository $footnoteRepository
    ) {
    }

    /**
     * @param array $conf TypoScript configuration for parseFunc
     */
    public function renderFootnotes(array $uids, array $conf = []): ?string
    {
        if ($uids === []) {
            return '';
        }

        $footnotes = $this->footnoteRepository->getFootnotesByUids($uids);
        if (count($footnotes) < 1) {
            return '';
        }

        /** @var Footnote $footnote */
        foreach ($footnotes as $footnote) {
            if ($footnote instanceof Footnote) {
                $footnote->setDescription(trim($this->renderRichText($footnote->getDescription(), $conf)));
            }
        } //render html in footnotes

        $templatePath = $this->getTemplatePath();

        $view = $this->createView($templatePath);
        $view->assign('footnotes', $footnotes);
        return $view->render();
    }

    /**
     * Renders the content of a RTE field.
     *
     * @param string $richText
     * @param array $conf TypoScript configuration for parseFunc
     * @param string $ref Reference to get configuration from. Eg. "< lib.parseFunc" which means that the configuration
     *                      of the object path "lib.parseFunc" will be retrieved and MERGED with what is in $conf!
     * @return string
     */
    public function renderRichText($richText, array $conf = [], ?string $ref = '< lib.parseFunc_HappyFeet')
    {
        if ($richText === '') {
            return '';
        }

        return $this->getContentObjectRenderer()
            ->parseFunc($richText, $conf, $ref);
    }

    protected function getContentObjectRenderer(): ContentObjectRenderer
    {
        if ($this->contentObjectRenderer === null) {
            $this->contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        }

        return $this->contentObjectRenderer;
    }

    private function createView(string $template): StandaloneView
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($template));
        return $view;
    }

    private function getTemplatePathAndFilename(string $template): string
    {
        return 'EXT:happy_feet/Resources/Private/Templates/Rendering/' . $template . '.html';
    }

    /**
     * checks if a alternative template is defined via typoscript setup configuration and if it exists as a file
     *
     * @return string
     */
    private function getTemplatePath()
    {
        /** @var TypoScriptFrontendController $tsfe */
        $tsfe = ($GLOBALS['TSFE'] ?? false);
        if (isset($tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'])) {
            $templateFile = GeneralUtility::getFileAbsFileName(
                $tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template']
            );
            if (is_file($templateFile)) {
                return $tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'];
            }
        }

        return $this->getTemplatePathAndFilename(self::DEFAULT_TEMPLATE);
    }
}
