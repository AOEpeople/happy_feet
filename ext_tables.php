<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// configure range of endtime here (and NOT in Configuration/TCA/[tablename].php), because the configuration-data is not cacheable!
$GLOBALS['TCA']['tx_happyfeet_domain_model_footnote']['columns']['endtime']['config']['range'] = array(
    'upper' => mktime(0, 0, 0, 12, 31, date('Y') + 10),
    'lower' => mktime(0, 0, 0, date('m') - 1, date('d'), date('Y'))
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_happyfeet_domain_model_footnote');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/', 'Happy Feet Footnote');
