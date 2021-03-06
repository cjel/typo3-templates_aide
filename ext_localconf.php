<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Cjel.TemplatesAide',
            'Dummy',
            [
                'Dummy' => 'list'
            ],
            // non-cacheable actions
            [
                'Dummy' => ''
            ]
        );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    dummy {
                        iconIdentifier = templates_aide-plugin-dummy
                        title = LLL:EXT:templates_aide/Resources/Private/Language/locallang_db.xlf:tx_templates_aide_dummy.name
                        description = LLL:EXT:templates_aide/Resources/Private/Language/locallang_db.xlf:tx_templates_aide_dummy.description
                        tt_content_defValues {
                            CType = list
                            list_type = templatesaide_dummy
                        }
                    }
                }
                show = *
            }
       }'
    );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'templates_aide-plugin-dummy',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:templates_aide/Resources/Public/Icons/user_plugin_dummy.svg']
			);
		
    }
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1549297828] = [
   'nodeName' => 'additionalHelpText',
   'priority' => 30,
   'class' => \Cjel\TemplatesAide\FormEngine\AdditionalHelpText::class,
];

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['c'] = [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['c'][]
  = 'Cjel\TemplatesAide\ViewHelpers';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['n'] = [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['n'][]
  = 'GeorgRinger\News\ViewHelpers';

call_user_func(
  function()
  {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['script_enabled'] =
      \Cjel\TemplatesAide\Controller\EIDController::class
      . '::scriptEnabled';
    if (isset($_SERVER['REQUEST_URI'])) {
      $uriParts = explode('/', $_SERVER['REQUEST_URI']);
      if ($uriParts[1] === 'script' && $uriParts[2] === 'enabled') {
        $_GET['eID'] = 'script_enabled';
      }
    }
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['script_disabled'] =
      \Cjel\TemplatesAide\Controller\EIDController::class
      . '::scriptDisabled';
    if (isset($_SERVER['REQUEST_URI'])) {
      $uriParts = explode('/', $_SERVER['REQUEST_URI']);
      if ($uriParts[1] === 'script' && $uriParts[2] === 'disabled') {
        $_GET['eID'] = 'script_disabled';
      }
    }
    //$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['script_enable'] =
    //    \Cjel\TemplatesAide\Controller\EIDController::class
    //        . '::scriptEnable';


  }
);
