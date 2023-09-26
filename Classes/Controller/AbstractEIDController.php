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

use Cjel\TemplatesAide\Traits\FormatResultTrait;
use Cjel\TemplatesAide\Traits\ValidationTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DefaultRestrictionContainer;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Reflection\ClassSchema;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
use TYPO3\CMS\Frontend\Utility\EidUtility;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\ResponseFactory;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * AbstractEIDController
 */
class AbstractEIDController
{

    /**
     * ValidationTrait
     */
    use ValidationTrait {
        validateAgainstSchema as traitValidateAgainstSchema;
    }

    /**
     * FormatResultTrait
     */
    use FormatResultTrait;

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

    /**
     * uriMapping
     */
    protected $uriMapping = null;

    /**
     * uriMappingValues
     */
    protected $uriMappingValues = [];


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
    public function __construct(ObjectManager $objectManager = null,
        array $configuration = [])
    {
        $this->objectManager = GeneralUtility::makeInstance(
            ObjectManager::class
        );
        $this->initFrontendController();
        $this->configurationManager = GeneralUtility::makeInstance(
            ConfigurationManagerInterface::class
        );
        $this->apiUtility = GeneralUtility::makeInstance(
            \Cjel\TemplatesAide\Utility\ApiUtility::class
        );
        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            $this->getExtensionKey()
        );
        $this->configurationManager->setConfiguration(
            $frameworkConfiguration
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
        $this->logManager = GeneralUtility::makeInstance(
            LogManager::Class
        );
        $this->importLogger = $this->logManager->getLogger(
            'importLogger'
        );
        $this->reflectionService = GeneralUtility::makeInstance(
            ReflectionService::class
        );
        $classInfo = $this->reflectionService->getClassSchema(
            get_class($this)
        );
        foreach ($classInfo->getInjectMethods() as $method => $className) {
            if (version_compare(TYPO3_branch, '10.0', '>=')) {
                $className = $className
                    ->getFirstParameter()
                    ->getDependency();
            }
            $class = GeneralUtility::makeInstance(
                $className
            );
            $this->{$method}($class);
        }
    }

    /**
     * Initialize frontentController
     */
    private function initFrontendController()
    {
        $currentDomain = strtok(GeneralUtility::getIndpEnv('HTTP_HOST'), ':');
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_domain');
        $queryBuilder->setRestrictions(
            GeneralUtility::makeInstance(DefaultRestrictionContainer::class)
        );
        $result = $queryBuilder
            ->select('uid', 'pid', 'domainName')
            ->from('sys_domain')
            ->where(
                $queryBuilder->expr()->eq(
                    'domainName',
                    $queryBuilder->createNamedParameter(
                        $currentDomain,
                        \PDO::PARAM_STR
                    )
                )
            )
            ->orderBy('sorting', 'ASC')
            ->execute()
            ->fetchAll();
        //if (count($result) < 1) {
        //    throw new \Exception('Domain not configured');
        //}


        $context = GeneralUtility::makeInstance(Context::class);




        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId(1049);

        $siteLanguage = reset($site->getLanguages());

        $pageArguments = GeneralUtility::makeInstance(
            PageArguments::class,
            1049,
            '0',
            []
        );

        $frontendUser = GeneralUtility::makeInstance(
            FrontendUserAuthentication::class
        );




        $frontendController = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            $context,
            $site,
            $siteLanguage,
            $pageArguments,
            $frontendUser

        );

        //die;
        $GLOBALS['LANG'] = GeneralUtility::makeInstance(LanguageService::class);
        $GLOBALS['LANG']->init('default');
        $GLOBALS['TSFE'] = $frontendController;
        //$frontendController->connectToDB();
        //$frontendController->fe_user = EidUtility::initFeUser();
        //$frontendController->id = 1049;
        $frontendController->determineId();
        //$frontendController->initTemplate();
        $frontendController->getConfigArray();
        //EidUtility::initTCA();
    }

    /**
     * process incoming requst
     *
     * checks if there is a method avaiable for the request and executes it, if
     * found
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return void
     */
    public function processRequest(
        ServerRequestInterface $request,
        ResponseInterface $response = null
    ) {
        if (!$response) {
            $responseFactory = GeneralUtility::makeInstance(
                ResponseFactory::class
            );
            $response = $responseFactory->createResponse();
        }
        $apiObject = explode('/', $request->getUri()->getPath())[3];
        $apiObjectId = explode('/', $request->getUri()->getPath())[4];
        if (!$apiObject) {
            return $response->withStatus(404);
        }
        $httpMethod = strtolower($request->getMethod());
        if ($this->uriMapping) {
            $uriParts = explode('/', $request->getUri()->getPath());
            $uriParts = array_slice($uriParts, 3);
            foreach ($this->uriMapping[$httpMethod] as $mapping => $function) {
                $mappingParts = explode('/', $mapping);
                $mappingParts = array_slice($mappingParts, 1);
                $max = max(count($mappingParts), count($uriParts));
                $mappingMatching = true;
                for ($i = 0; $i < $max; $i++) {
                    if ($uriParts[$i] == $mappingParts[$i]) {
                        continue;
                    }
                    if (
                        $uriParts[$i]
                        && substr($mappingParts[$i], 0, 1) == '{'
                        && substr($mappingParts[$i], -1, 1) == '}'
                    ) {
                        $mappingKey = substr($mappingParts[$i], 1, -1);
                        $this->uriMappingValues[$mappingKey] = $uriParts[$i];
                        continue;
                    }
                    $mappingMatching = false;
                }
                if ($mappingMatching == true) {
                    $requestMethod = $function . 'Request';
                }
            }
        } else {
            if ($apiObjectId) {
                $requestMethod = $httpMethod
                    . ucfirst($apiObject)
                    . 'SingleRequest';
                $request->apiObjectId = $apiObjectId;
            } else {
                $requestMethod = $httpMethod
                    . ucfirst($apiObject)
                    . 'Request';
            }
        }
        if (method_exists($this, $requestMethod)) {
            $responseData = $this->$requestMethod($request, $response);
            $response = $response->withHeader(
                'Content-Type',
                'application/json; charset=utf-8'
            );
            if (is_array($responseData)
                && array_key_exists('errors', $responseData)
            ) {
                $response = $response->withStatus(400);
            }
            if (is_array($responseData)
                && array_key_exists('status', $responseData)
            ) {
                if (is_array($responseData['status'])) {
                    $response = $response->withStatus(
                        $responseData['status'][0],
                        $responseData['status'][1]
                    );
                } else {
                    $response = $response->withStatus($responseData['status']);
                }
            }
            $response->getBody()->write(\json_encode($responseData));
            return $response;
        } else {
            return $response->withStatus(404);
        }
    }

    /**
     *
     */
    public function persistAll()
    {
        (GeneralUtility::makeInstance(
            PersistenceManager::class
        ))->persistAll();
    }


    /**
     * return function
     *
     * @param array $result
     * @return void
     */
    protected function returnFunction(
        $result      = []
    ) {
        $result = $this->formatResult($result, 'asd');
        unset($result['cid']);
        unset($result['componentMode']);
        unset($result['isValid']);
        if ($result) {
            if (!empty($this->errors)) {
                return $result;
            } else {
                return [
                    'metadata' => [
                        'total' => count($result),
                        'count' => count($result),
                    ],
                    'result' => $result,
                ];
            }
        } else {
            return [];
        }
        //return $result;
    }

}
