<?php

namespace wbs\Framework\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\ORMSetup;
use wbs\Framework\Config\ENV;
use wbs\Framework\WbsClass;
use RuntimeException;

/**
 * Class Doctrine: Konfiguration und Zugriff auf den Entity Manager
 *
 * @package wbs\Framework\Doctrine
 */
class Doctrine extends WbsClass {
    /**
     * @var EntityManager
     */
    protected $entity_manager;
    /**
     * @return EntityManager
     */
    public function entity_manger()
    {

        if(is_null($this->entity_manager)){
            $this->entity_manager = $this->getEntityManager();

        }
        return $this->entity_manager;
    }
    /**
     * Source was taken from
     * https://www.doctrine-project.org/projects/doctrine-orm/en/3.1/tutorials/getting-started.html
     *
     * @param bool $isDevMode
     *
     * @return EntityManager
     */
    protected function getEntityManager($isDevMode =true){

        /**
         * Check all Entities in the wbs Directory
         */
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [ $this->wbs()->getRootPath().'wbs/'],
            $isDevMode,
            $this->wbs()->getCachePath().'doctrine/',
        );
        // configuring the database connection
        $connection = DriverManager::getConnection([
                                                       'driver' => 'pdo_mysql',
                                                       'path' => __DIR__ . '/db.sqlite',
                                                   ], $config);// configuring the database connection

//        $config = ORMSetup::createConfiguration(
//            $isDevMode,
//            $this->wbs()->getCachePath().'doctrine/',
//        );
//            [ $this->wbs()->getRootPath().'wbs/'],
//            $this->wbs()->getTempPath().'orm_proxy/',
//            null,
//            false
//        var_dump($config);

        /**************************************************************************
         *   MySQL Configuration
         *************************************************************************/
        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => $this->wbs()->env(ENV::MYSQL_USER),
            'password' => $this->wbs()->env(ENV::MYSQL_PASSWORD),
            'dbname'   => $this->wbs()->env(ENV::MYSQL_DATABASE),
            'charset'  => 'utf8mb4',
            'driverOptions' => array(
                1002 => 'SET NAMES utf8mb4'
            ),
            'defaultTableOptions' => array(
                'charset' => 'utf8mb4',
                'collate' => 'utf8mb4_unicode_ci',
                'row_format' => 'DYNAMIC'
            )
        );
//        var_dump($dbParams);
        $conn= DriverManager::getConnection(
            $dbParams,
            $config
        );
        // obtaining the entity manager
        try {
            /** @noinspection PhpUnnecessaryLocalVariableInspection */
            $entityManager = new EntityManager(
                $conn,
                $config
            );
            return $entityManager;

        } catch (ORMException $e) {
            throw new RuntimeException('ORM Exception: '.$e->getMessage());
        }

    }

}