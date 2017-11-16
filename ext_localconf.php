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

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
    TCEMAIN.linkHandler {
      happyfeet {
        handler = Cobweb\Linkhandler\RecordLinkHandler
        label = LLL:EXT:happy_feet/Resources/Private/Language/locallang_db.xml:tx_happyfeet_domain_model_footnote
        configuration.table = tx_happyfeet_domain_model_footnote
        scanBefore = page
      }
    }

    RTE {
      classes.happy_feet.name = Happy Feet
      classesAnchor {
        happyfeet {
          class = happy_feet
          type = happyfeet
        }
      }

      default.buttons.link {
        properties.class.allowedClasses := addToList(happy_feet)
        happyfeet.properties.class.default = happy_feet
      }
    }
');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['linkhandler']['generateLink']['happy_feet'] = 'Aoe\HappyFeet\Typo3\Service\LinkHandler';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['happy_feet'] =
    'Aoe\HappyFeet\Typo3\Hook\Tcemain';