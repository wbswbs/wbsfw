<?php

namespace wbs\Framework;


use wbs\Framework\Db\Database;
use wbs\Framework\Db\MySql;
use PDO;

/**
 * Class wbs, Blaupause für wbs Klassen
 *
 * @package de\write2gether\classes\xpl
 */
class WbsDbClass extends WbsClass
{
    /**
     * @var \wbs\Framework\Db\Database
     */
    protected $database;
    /**
     * @var MySql
     */
    protected $mysql;

    /**
     * @return MySql
     */
    public function mysql(){

        if(is_null($this->mysql)){
            $this->mysql = new  MySql($this->wbs());
        }
        return $this->mysql;
    }
    /**
     * @return PDO
     */
    public function pdo(){

        return $this->mysql()->pdo();
    }

    /**
     * @return \wbs\Framework\Db\Database
     * @throws \Exception
     */
    public function database(){

        if(is_null($this->database)){
            $this->database = new Database($this->wbs());
        }
        return $this->database;
    }
}
