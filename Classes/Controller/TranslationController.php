<?php
namespace Cjel\TemplatesAide\Controller;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2024 Philipp Dieter <philippdieter@attic-media.net>
 *
 ***/

use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * TranslationController
 */
class TranslationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    protected static $locallangPath = 'Resources/Private/Language/';

    /**
     * action translations
     *
     * @param array $extensions
     * @return void
     */
    public function translationsAction($extensions = [])
    {
        $result = [];
        foreach ($extensions as $extension) {
            $langfilePath = 'EXT:'
                . GeneralUtility::camelCaseToLowerCaseUnderscored($extension)
                . '/'
                . self::$locallangPath
                . 'locallang.xlf';
            $languageFactory = GeneralUtility::makeInstance(
                LocalizationFactory::class
            );
            $langfileContent = $languageFactory->getParsedData(
                $langfilePath,
                $GLOBALS['LANG']->lang
            );
            $langfileResult = [];
            foreach (reset($langfileContent) as $key => $row) {
                $langfileResult[$key] = reset($row)['target'];
            }
            $result[$extension] = $langfileResult;
        }
        $GLOBALS['TSFE']->setContentType('application/json');
        return json_encode($result);
    }
}
