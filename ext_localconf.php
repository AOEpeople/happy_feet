<?php

use AOE\HappyFeet\Typo3\Hook\LinkHandler;
use AOE\HappyFeet\Typo3\Hook\LinkRenderer;
use AOE\HappyFeet\Typo3\Hook\Tcemain;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addPageTSConfig(
    "@import 'EXT:happy_feet/Configuration/TsConfig/Page/RTE.tsconfig'"
);

ExtensionManagementUtility::addPageTSConfig(
    "@import 'EXT:happy_feet/Configuration/TsConfig/Page/TceMain.tsconfig'"
);

#$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['happy_feet'] =
#    Tcemain::class;

$GLOBALS['TYPO3_CONF_VARS']['FE']['typolinkBuilder']['happy_feet'] = LinkRenderer::class;
$GLOBALS['TYPO3_CONF_VARS']['SYS']['linkHandler']['happy_feet'] = LinkHandler::class;
