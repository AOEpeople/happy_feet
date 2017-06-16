<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$extKey = 'happy_feet';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '
        # ***************************************************************************************
        # CONFIGURATION of RTE in table "tx_happyfeet_domain_model_footnote", field "description"
        # ***************************************************************************************
        RTE.config.tx_happyfeet_domain_model_footnote.description {
          disableColorPicker = 1
          proc.exitHTMLparser_db=1
          proc.exitHTMLparser_db {
            allowTags = span, b, strong, i, em, u, a, h1, h2, h3, h4, h5, h6, pre
            tags.div.remap = P
          }
        }

        TCEFORM.tx_happyfeet_domain_model_footnote {
            sys_language_uid.disabled = 1
        }
    '
);

$typo3Version = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(
    \TYPO3\CMS\Core\Utility\VersionNumberUtility::getNumericTypo3Version()
);

if ($typo3Version < 7006000) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        RTE.default.tx_linkhandler {
          happy_feet {
            label=Happy Feet Fußnoten
            overwriteHandler=happyfeet
            noAttributesForm=1
            linkClassName=happy_feet
          }
        }
        
        mod.tx_linkhandler.happy_feet < RTE.default.tx_linkhandler.happy_feet
    ');

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler'][$extKey] =
        Aoe\HappyFeet\Typo3\Service\v62\LinkHandler::class;

} else {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        TCEMAIN.linkHandler {
          happyfeet {
            handler = Cobweb\Linkhandler\RecordLinkHandler
            label = LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xml:tx_happyfeet_domain_model_footnote
            configuration.table = tx_happyfeet_domain_model_footnote
            scanBefore = page
          }
        }
    ');

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['linkhandler']['generateLink'][$extKey] =
        Aoe\HappyFeet\Typo3\Service\LinkHandler::class;
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extKey] =
    Aoe\HappyFeet\Typo3\Hook\Tcemain::class;