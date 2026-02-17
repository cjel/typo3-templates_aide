<?php
namespace Cjel\TemplatesAide\Utility;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Philipp Dieter <philipp.dieter@attic-media.net>
 *
 ***/

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 *
 */
class TranslationUtility
{

    /**
     * Get all interface constants per prefix
     */
    public static function buildSelectOptionsFromOptions(
        $options,
        $column,
        $element,
        $extensionKey = null
    ) {
        $items = [];
        if ($addEmpty) {
            $items[] = ['-', ''];
        }
        foreach ($options as $option) {
            $translationKey = "option.$element.$column.$option";
            $translation = self::getTranslation(
                $translationKey,
                $extensionKey
            );
            if ($translation) {
                $items[] = [
                    'code'  => $option,
                    'label' => $translation,
                ];
            } else {
                $items[] = [
                    'code'  => $option,
                    'label' => $translationKey,
                ];
            }
        }
        return $items;
    }

    /**
     * shortcut to get translation
     *
     * @return void
     */
    public static function getTranslation($key, $extensionKey)
    {
        if (version_compare(TYPO3_branch, '10.0', '>=')) {
            if (!$extensionKey) {
                $extensionKey = 'site_templates';
            }
            $translation = LocalizationUtility::translate(
                'LLL:EXT:'
                    . $extensionKey
                    . '/Resources/Private/Language/locallang_db.xlf:'
                    . $key
            );
            return $translation;
        } else {
            if ($extensionKey) {
                $translation = LocalizationUtility::translate(
                    $key,
                    $extensionKey
                );
                if ($translation) {
                    return $translation;
                }
            }
            $translation = LocalizationUtility::translate(
                $key,
                'site_templates'
            );
            if ($translation) {
                return $translation;
            }
            return null;
        }
    }
}
