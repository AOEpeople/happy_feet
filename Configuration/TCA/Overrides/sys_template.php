<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// Register static TypoScript templates
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('happy_feet', 'Configuration/TypoScript/', 'Happy Feet Footnote');
