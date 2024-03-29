<?php
namespace Cjel\TemplatesAide\Utility;

/***
 *
 * This file is part of the "" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Philipp Dieter
 *
 ***/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\TypoScript\Parser\TypoScriptParser;

/**
 * Utility to work with site config
 */
class SiteConfigUtility
{
    /**
     * Gets site config by typoscript path
     *
     * @var string $path
     * @return string
     */
    public static function getByPath(
        $path,
        $limitToSiteConfig = true
    ) {
        $pathParts = explode('.', $path);
        $objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $typoScriptParser = GeneralUtility::makeInstance(
            TypoScriptParser::class
        );
        $configurationManager = $objectManager->get(
            ConfigurationManagerInterface::class
        );
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $siteConfig = $typoscript;
        if ($limitToSiteConfig) {
            $siteConfig = $typoscript['config.']['site.'];
        }
        $current = &$siteConfig;
        foreach ($pathParts as $key) {
            if ($current[$key . '.']) {
                $key .= '.';
            }
            $current = &$current[$key];
            if (isset($current[0]) && $current[0] === '<') {
                $searchkey = trim(substr($current, 1));
                list($name, $conf) = $typoScriptParser->getVal(
                  $searchkey,
                  $typoscript
                );
                $current = $conf;
            }
        }
        if (is_array($current)
            && array_key_exists('value', $current)
            && count($current) === 1
        ) {
            $current = $current['value'];
        }
        return $current;
    }

    /**
     * Gets extension config by typoscript path
     *
     * @var string $path
     * @return string
     */
    public static function getFromExtensionByPath(
        $extensionName,
        $path
    ) {
        $pathParts = explode('.', $path);
        $objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $configurationManager = $objectManager->get(
            ConfigurationManagerInterface::class
        );
        $typoscript = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            $extensionName
        );
        $current = &$typoscript;
        foreach ($pathParts as $key) {
            if (
                !is_array($current)
                || !array_key_exists($key, $current)
            ) {
                return null;
            }
            $current = &$current[$key];
        }
        return $current;
    }
}
