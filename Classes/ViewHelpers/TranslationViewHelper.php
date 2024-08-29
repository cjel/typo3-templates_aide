<?php
namespace Cjel\TemplatesAide\ViewHelpers;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Philipp Dieter <philipp.dieter@attic-media.net>
 *
 ***/

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class TranslationViewHelper extends AbstractViewHelper
{
   use CompileWithRenderStatic;

   /**
    * Initialize arguements
    *
    * @return void
    */
   public function initializeArguments()
   {
       $this->registerArgument(
           'key',
           'string',
           'The translation key to render',
           true
       );
       $this->registerArgument(
           'extensionKey',
           'string',
           'The extension key to search in',
           false,
           'site_templates'
       );
       $this->registerArgument(
           'arguments',
           'array',
           'The arguments',
           false,
           false
       );
   }

   /**
    * Render tranlation
    *
    * @param $arguments array arguments
    * @param $renderChildrenClosure Closure
    * @param $renderingContext $renderChildrenClosure
    * @return string
    */
   public static function renderStatic(
       array $arguments,
       \Closure $renderChildrenClosure,
       RenderingContextInterface $renderingContext
   ) {
        $translation = LocalizationUtility::translate(
            $arguments['key'],
            $arguments['extensionKey'],
            (array) $arguments['arguments']
        );
        if ($translation) {
            return $translation;
        }
       return $arguments['extensionKey'] . ': ' . $arguments['key'];
   }
}
