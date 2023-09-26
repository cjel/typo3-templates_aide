<?php
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::registerPageTSConfigFile(
    'templates_aide',
    'Resources/Private/PageTSConfig/default.tsconfig',
    'Default Config'
);
