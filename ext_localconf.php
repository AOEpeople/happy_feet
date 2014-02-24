<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['happy_feet'] = 'EXT:happy_feet/Classes/Typo3/Hooks/Tcemain.php:Tx_HappyFeet_Typo3_Hooks_Tcemain';