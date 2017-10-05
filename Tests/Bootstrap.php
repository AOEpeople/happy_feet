<?php
require_once dirname(__FILE__) . '/../../../../typo3/sysext/core/Build/UnitTestsBootstrap.php';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['extbase_object'] = array(
    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
    'options' => array()
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template'] = array(
    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend',
    'frontend' => 'TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend',
    'groups' => array('system')
);

\TYPO3\CMS\Core\Core\Bootstrap::getInstance()->initializeCachingFramework();
