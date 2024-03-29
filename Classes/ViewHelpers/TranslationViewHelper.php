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
       return 'this.extension.translation';
   }
}
