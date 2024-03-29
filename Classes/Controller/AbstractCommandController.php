<?php
namespace Cjel\TemplatesAide\Controller;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2021 Philipp Dieter <philippdieter@attic-media.net>
 *
 ***/

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DefaultRestrictionContainer;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use TYPO3\CMS\Frontend\Utility\EidUtility;
use TYPO3\CMS\Extbase\Reflection\ClassSchema;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
use Symfony\Component\Console\Command\Command;

/**
 * AbstractEIDController
 */
class AbstractCommandController extends Commands
{

    /**
     * @var BackendConfigurationManager
     */
    protected $configurationManager;

    /**
     * ApiUtility
     */
    protected $apiUtility = null;

    /*
     * extension Key
     */
    protected $extensionKey = null;

    /*
     * objectManager
     */
    protected $objectManager = null;

    /*
     * storagePids
     */
    protected $settings = null;

    /*
     * storagePids
     */
    protected $storagePids = [];

    /*
     * logManager
     */
    protected $logManager = null;

    /*
     * logger
     */
    protected $importLogger = null;


    /*
     * returns the extensionkey set in the exended calss
     */
    public function getExtensionKey() {
        return $this->extensionKey;
    }

    /**
     * Construct
     *
     * @param ObjectManager $objectManager
     * @param array         $configuration
     */
    public function __construct(
        ObjectManager $objectManager = null,
        array $configuration = []
    ) {
        $this->objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $this->initFrontendController();
        $this->configurationManager = $this->objectManager->get(
            ConfigurationManagerInterface::class
        );
        $this->apiUtility = $this->objectManager->get(
            \Cjel\TemplatesAide\Utility\ApiUtility::class
        );
        $this->configurationManager->setConfiguration(array());
        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            $this->getExtensionKey()
        );
        $this->settings = $frameworkConfiguration;
        $this->storagePids = explode(
            ',',
            str_replace(
                ' ',
                '',
                $frameworkConfiguration['persistence']['storagePid']
            )
        );
        $this->logManager = $this->objectManager->get(
            LogManager::Class
        );
        $this->importLogger = $this->logManager->getLogger(
            'importLogger'
        );
        $this->reflectionService = GeneralUtility::makeInstance(
            ReflectionService::class, GeneralUtility::makeInstance(
                CacheManager::class
            )
        );
        $classInfo = $this->reflectionService->getClassSchema(
            get_class($this)
        );
        foreach ($classInfo->getInjectMethods() as $method => $className) {
            $class = $this->objectManager->get(
                $className
            );
            $this->{$method}($class);
        }
        parent::__construct($name);
    }

    /**
     * Initialize frontentController
     */
    private function initFrontendController()
    {
        $currentDomain = strtok(GeneralUtility::getIndpEnv('HTTP_HOST'), ':');
        $frontendController = GeneralUtility::makeInstance(
            \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            null,
            0,
            true
        );
        $GLOBALS['TSFE'] = $frontendController;
        $frontendController->connectToDB();
        $frontendController->fe_user = EidUtility::initFeUser();
        $frontendController->id = $result[0]['pid'];
        $frontendController->determineId();
        $frontendController->initTemplate();
        $frontendController->getConfigArray();
        EidUtility::initTCA();
    }

}
