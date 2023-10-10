<?php

namespace wbs\Framework;

use wbs\Framework\Db\MySql;
use wbs\Framework\File\Verzeichnis;
use wbs\Framework\Ftp\FtpParameter;
use wbs\Framework\Google\Calendar;
use wbs\Framework\Html\Html;
use wbs\Framework\Json\ApiResponse;
use wbs\Framework\Json\Json;
use wbs\Framework\Json\Response;
use wbs\Framework\Lieferanten\LieferantenController;
use wbs\Framework\Liefertermin\Liefertermin;
use wbs\Framework\Smarty\SmartyWrapper;
use wbs\Framework\Caching\Cache;

/**
 * Fabric Class for creation of needed classes -> factory pattern
 *
 * Class WbsFactory creates Instances of Classes
 */
class WbsFactory extends WbsClass
{

    /**
     * Return Instance of the API Reguest
     *
     * @return \wbs\Framework\Api\ApiController
     */
    public function getApi()
    {
        return new \wbs\Framework\Api\ApiController($this->wbs());
    }

    /**
     * @return FtpParameter
     */
    public function getFtpParameter()
    {
        return new FtpParameter();
    }

    /**
     * Not in use
     * api/calendar.php is used now
     *
     * @return Calendar
     */
    public function getGoogleCalendar()
    {

        return new Calendar(
            $this->wbs()->getConfigPath() . 'calendar.php'
        );
    }

    /**
     *
     * @return \wbs\Framework\Html\Html
     */
    public function getHtml()
    {
        return new Html($this->wbs());
    }
    /**
     *
     * @return \wbs\Framework\Json\Json
     */
    public function getJson()
    {
        return new Json();
    }

    /**
     * @return Response
     */
    public function getJsonResponse()
    {
        return new Response();
    }

    /**
     * @return LieferantenController
     */
    public function getLieferanten()
    {
        return new LieferantenController($this->wbs());
    }
    /**
     * @return Liefertermin
     */
    public function getLiefertermin()
    {

        return new Liefertermin($this->wbs());
    }

    /**
     * @return MySql
     */
    public function getMySql()
    {
        return new MySql(
            $this->wbs()
        );
    }


    /**
     * Get an Instance of the Smarty Wrapper with the given Template Folder
     *
     * @param string $template
     *
     * @return SmartyWrapper
     */
    public function getSmartyWrapper($template)
    {

        return new SmartyWrapper(
            $this->wbs(),
            $this->wbs()->getTemplatePath(),
            $this->wbs()->getCachePath() . 'smarty/',
            $this->wbs()->getTempPath() . 'smarty/',
            (string)$template
        );
    }


    /**
     * Einen wbs Controller für das Projekt laden
     *
     * Konvention:
     *
     * Controller liegen in /wbs/{controller_name}/{controller_name}Controller}.php
     *
     * mit dem Namespace \wbs\{controller_name}\
     *
     * @var string $controller_name Erstes Zeichen in Großbuchstabben
     *
     * @return mixed
     *
     */
    public function getwbsController($controller_name)
    {
        if(strtoupper($controller_name[0]) !== $controller_name[0]){
           throw new \Exception('Controller Name must have first letter uppercase ');
        }
//        $class= 'wbs\\Station\\StationController';
        $class= 'wbs\\'.$controller_name.'\\'.$controller_name.'Controller';

        if(!class_exists($class)){
            throw new \Exception('Class does not exist: '.$class);
        }
        $instance = '\wbs\\'.$controller_name.'\\'.$controller_name.'Controller';
        return new ${$instance}($this->wbs());
    }
    /**
     * Den Station Controller für das Station Projekt laden
     *
     * @return \wbs\Station\StationController
     *
     */
    public function getStationController()
    {
        $class= 'wbs\\Station\\StationController';
        if(!class_exists($class)){
            throw new \Exception('Class does not exist: '.$class);
        }
        return new \wbs\Station\StationController($this->wbs());
    }
    /**
     *
     * @return \wbs\Station\Station
     *
     */
    public function getStation()
    {
        $class= 'wbs\\Station\\Station';
        if(!class_exists($class)){
            throw new \Exception('Class does not exist: '.$class);
        }
        return new \wbs\Station\Station($this->wbs());
    }

    /**
     * @param $filepath
     * @param $filename
     *
     * @return \wbs\Framework\Caching\Cache
     */
    public function getCache($filepath, $filename)
    {
        return new Cache($this->wbs(), $filepath, $filename);
    }

    public function getVerzeichnis($path)
    {
        return new Verzeichnis($this->wbs(), $path);
    }

}