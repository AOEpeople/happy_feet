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

use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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
     * @var \TYPO3\CMS\Fluid\View\StandaloneView
     */
    private $view;

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
        $view = $this->createView();
        $view->assign('footnotes', $footnotes);
        return $view->render('Markup');
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
     * @return \TYPO3\CMS\Fluid\View\StandaloneView
     */
    private function createView()
    {
        if (null === $this->view) {
            $this->view = $this->getObjectManager()->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
            $this->view->setTemplatePathAndFilename($this->getTemplatePathAndFilename());
        }
        return $this->view;
    }

    /**
     * @return string
     */
    private function getTemplatePathAndFilename()
    {
        return ExtensionManagementUtility::extPath(
            'happy_feet',
            'Resources' . DIRECTORY_SEPARATOR . 'Private' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'Rendering' . DIRECTORY_SEPARATOR . 'Markup.html'
        );
    }
}
