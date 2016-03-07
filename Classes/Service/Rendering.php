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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @package HappyFeet
 * @subpackage Service_Test
 * @author Kevin Schu <kevin.schu@aoe.com>
 */
class Tx_HappyFeet_Service_Rendering extends Tx_HappyFeet_Service_Abstract
{
    /**
     * @var Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    private $footnoteRepository;

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
        $view = $this->createView('Markup');
        $view->assign('footnotes', $footnotes);
        return $view->render('Markup');
    }

    /**
     * Renders the content of a RTE field.
     *
     * @param string $richText
     * @return string
     */
    public function renderRichText($richText)
    {
        if (strlen($richText) < 1) {
            return '';
        }
        $view = $this->createView('RichText');
        $view->assign('richText', $richText);
        return $view->render('RichText');
    }

    /**
     * @return Tx_HappyFeet_Domain_Repository_FootnoteRepository
     */
    protected function getFootnoteRepository()
    {
        if (null === $this->footnoteRepository) {
            $this->footnoteRepository = $this->getObjectManager()->get(
                'Tx_HappyFeet_Domain_Repository_FootnoteRepository'
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
        $view = $this->getObjectManager()->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
        $view->setTemplatePathAndFilename($this->getTemplatePathAndFilename($template));
        return $view;
    }

    /**
     * @param string $template
     * @return string
     */
    private function getTemplatePathAndFilename($template)
    {
        return ExtensionManagementUtility::extPath(
            'happy_feet',
            'Resources' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR .
            'Rendering' . DIRECTORY_SEPARATOR . $template . '.html'
        );
    }

    /**
     * @param Tx_HappyFeet_Domain_Repository_FootnoteRepository $footnoteRepository
     */
    public function setFootnoteRepository(Tx_HappyFeet_Domain_Repository_FootnoteRepository $footnoteRepository)
    {
        $this->footnoteRepository = $footnoteRepository;
    }
}
