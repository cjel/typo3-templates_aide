<?php
namespace Cjel\TemplatesAide\Utility;

/***
 *
 * This file is part of the "Templates Aide" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Philipp Dieter
 *
 ***/

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

/**
 * Holds functions to help with database interactions
 */
class DatabaseUtility
{

    /**
     * Mysql date format
     */
    const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * Returns table name by model
     *
     * @param $model object model
     * @return string table name
     */
    public static function getTableNameFromModelClass($class)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $dataMapper = $objectManager->get(DataMapper::class);
        return $dataMapper->getDataMap($class)->getTableName();
    }

    /**
     * Creates a new query builder and returns it
     *
     * @param $tablename string table name
     * @return object queryBuilder
     */
    public static function getQueryBuilderFromTableName($tableName)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableName)
            ->createQueryBuilder();
        return $queryBuilder;
    }

    /**
     * Gets a connection for a table and returns it
     *
     * @param $tablename string table name
     * @return object connection
     */
    public static function getConnectionFromTableName($tableName)
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableName);
    }

    /**
     * testAndCreateIndex
     */
    public static function testAndCreateIndex(
        $table, $indexName, $indexColumns, $type
    ) {
        $connection = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getConnectionForTable($table);
        $existTestQuery = "
            SHOW TABLES LIKE '${table}'
        ";
        $existTestResult = $connection
            ->executeQuery($existTestQuery)
            ->fetchAll();
        if (!count($existTestResult)) {
            return;
        }
        $indexTestQuery = "
            SHOW INDEX FROM ${table}
            WHERE Key_name = '${indexName}'
        ";
        $indexTestResult = $connection
            ->executeQuery($indexTestQuery)
            ->fetchAll();
        if (count($indexTestResult)) {
            return;
        }
        switch ($type) {
        case 'btree':
            $queryCreate = "
                CREATE INDEX ${indexName}
                USING BTREE ON ${table} (${indexColumns})
            ";
            break;
        case 'fulltext':
            $queryCreate = "
                CREATE FULLTEXT INDEX ${indexName}
                ON ${table} (${indexColumns})
            ";
            break;
        }
        $connection->executeQuery($queryCreate);
    }
}
