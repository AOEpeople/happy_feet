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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class RenderingService extends AbstractService
{
    /**
     * @var FootnoteRepository
     */
    private $footnoteRepository;

    /**
     * @var string
     */
    private $defaultTemplate = 'Markup';

    /**
     * @param array $uids
     * @return string
     */
    public function renderFootnotes(array $uids)
    {
        if (empty($uids)) {
            return '';
        }
        $footnotes = $this->getFootnoteRepository()->getFootnotesByUids($uids);
        if (count($footnotes) < 1) {
            return '';
        }
        /** @var Footnote $footnote */
        foreach ($footnotes as $footnote){
            if ($footnote InstanceOf Footnote) {
                $footnote->setDescription($this->renderRichText($footnote->getDescription()));
            }
        } //render html in footnotes

        $templatePath = $this->getTemplatePath();

        $view = $this->createView($templatePath);
        $view->assign('footnotes', $footnotes);
        return $view->render($templatePath);
    }

    /**
     * Renders the content of a RTE field.
     *
     * @param string $richText
     * @return string
     */
    public function renderRichText($richText)
    {
        if ($richText === '') {
            return '';
        }

        $templatePath = $this->getTemplatePathAndFilename('RichText');

        $view = $this->createView($templatePath);
        $view->assign('richText', $richText);
        return $view->render($templatePath);
    }

    /**
     * @return FootnoteRepository
     */
    protected function getFootnoteRepository()
    {
        if (null === $this->footnoteRepository) {
            $this->footnoteRepository = $this->getObjectManager()->get(
                FootnoteRepository::class
            );
        }
        return $this->footnoteRepository;
    }

    /**
     * @param string $template
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     */
    private function createView($template)
    {
        $view = $this->getObjectManager()->get(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($template));
        return $view;
    }

    /**
     * @param string $template
     * @return string
     */
    private function getTemplatePathAndFilename($template)
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
        /**
         * @var $tsfe TypoScriptFrontendController
         */
        $tsfe = $GLOBALS['TSFE'];
        if (isset($tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'])) {
            $templateFile = GeneralUtility::getFileAbsFileName(
                $tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template']
            );
            if (is_file($templateFile)) {
                return $tsfe->tmpl->setup['lib.']['plugins.']['tx_happyfeet.']['view.']['template'];
            }
        }

        return $this->getTemplatePathAndFilename($this->defaultTemplate);
    }

    /**
     * @param FootnoteRepository $footnoteRepository
     */
    public function setFootnoteRepository(FootnoteRepository $footnoteRepository)
    {
        $this->footnoteRepository = $footnoteRepository;
    }
}
