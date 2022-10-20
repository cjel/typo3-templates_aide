<?php
namespace Cjel\TemplatesAide\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class CamelcaseToKebabcaseViewHelper extends AbstractViewHelper
{
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $string = preg_replace('/[\s.]+/', '_', $renderChildrenClosure());
        $string = preg_replace('/[^0-9a-zA-Z_\-]/', '-', $string);
        $string = strtolower(preg_replace('/[A-Z]+/', '-\0', $string));
        $string = trim($string, '-_');
        return preg_replace('/[_\-][_\-]+/', '-', $string);
    }
}
