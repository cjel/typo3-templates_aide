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
            if (version_compare(TYPO3_branch, '10.0', '>=')) {
                $language = $this->request->getAttribute('language');
            } else {
                $language = $GLOBALS['TYPO3_REQUEST']->getAttribute('language');
            }
            $language = $language->getTypo3language();
            $langfileContent = $languageFactory->getParsedData(
                $langfilePath,
                $language
            );
            $langfileResult = [];
            foreach (reset($langfileContent) as $key => $row) {
                $langfileResult[$key] = reset($row)['target'];
            }
            foreach ($langfileContent[$language] as $key => $row) {
                $langfileResult[$key] = reset($row)['target'];
            }
            $result[$extension] = $langfileResult;
        }
        $GLOBALS['TSFE']->setContentType('application/json');
        return json_encode($result);
    }
}
