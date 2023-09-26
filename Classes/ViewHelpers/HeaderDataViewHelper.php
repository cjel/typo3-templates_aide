<?php
namespace Cjel\TemplatesAide\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HeaderDataViewHelper extends AbstractViewHelper
{

    /**
      * As this ViewHelper renders HTML, the output must not be escaped.
      *
      * @var bool
      */
    protected $escapeOutput = false;

    /**
     * @param string $file select case
     * @param string $data the data
     * @return html HTML Content
     */
    public function render($type, $data = False){
        if ($data === False) {
            $data = $this->renderChildren();
        }
        switch ($type){
            case 'tracking':
                    $GLOBALS['TSFE']->additionalHeaderData[] = $data;
                break;
            case 'title':
                $GLOBALS['TSFE']->additionalHeaderData[]
                    = '<title>' . $data . '</title>';
                break;
            case 'favicon':
                $GLOBALS['TSFE']->additionalHeaderData[]
                    = '<link rel="shortcut icon" type="image/x-icon" href="'
                        . $data
                        . '" />';
                break;
            case 'raw':
                $GLOBALS['TSFE']->additionalHeaderData[] = $data;
                break;
        }
    }
}
