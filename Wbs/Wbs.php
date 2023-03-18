<?php

namespace wbsfw\Framework;

use Composer\InstalledVersions;
use Exception;
use wbs\Framework\Api\ApiController;
use wbs\Framework\Config\ENV;
use wbs\Framework\Db\MySql;
use wbs\Framework\Doctrine\Doctrine;
use wbs\Framework\Filter\Filter;
use wbs\Framework\Ip\Ip;
use wbs\Framework\Json\Response;
use wbs\Framework\Log\Log;
use wbs\Framework\LogDB\LogDBController;
use wbs\Framework\Mail\Smtp;
use wbs\Framework\Websocket\Websocket;
use Psr\Log\LoggerInterface;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Minimale Test Version für Composer
 *
 * Test:
 * $wbs = new Wbs('');
 * echo $wbs->getVersion();
 *
 */
class Wbs
{
    /**
     * Base Path of the Project
     *
     * @var string
     */
    protected $root_dir;
    /**
     * @var string
     */
    protected $url_absolute;
    /**
     * Current Version of the Framework
     *
     * @var string
     */
    protected $version = '2.5.0';
    /**
     * @var \wbs\Framework\Api\ApiController
     */
    private $api;
    /**
     * @var Dotenv
     */
    private $dotenv;
    /**
     * Instanz der Job Klasse
     *
     * @var Auftrag
     */
    private $auftrag;
    /**
     * Instanz der Klasse doctrine
     *
     * @var Doctrine
     */
    private $doctrine;
    /**
     * Instanz der Factory Klasse
     *
     * @var wbsFactory
     */
    private $factory;

    /**
     * @var Filter
     *
     */
    private $filter;

    /**
     * Instanz der HTML KLasse
     *
     * @var \wbs\Framework\Html\Html
     */
    private $html;
    /**
     * Instanz der IP KLasse
     *
     * @var \wbs\Framework\Ip\Ip
     */
    private $ip;
    /**
     * Instanz der Json KLasse
     *
     * @var \wbs\Framework\Json\Json
     */
    private $json;
    /**
     * Instanz der Log Klasse
     *
     * @var Log
     */
    private $log;
    /**
     * Instanz der LogDB Klasse
     *
     * @var \wbs\Framework\LogDB\LogDBController
     */
    private $log_db;
    /**
     * Instanz des SmartyWrappers
     *
     * @var \wbs\Framework\Smarty\SmartyWrapper
     */
    private $smarty;
    /**
     * Instanz des Station Controllers
     *
     * @var \wbs\Framework\Station\StationController
     */
    private $station_controller;
    /**
     * Instanz der SMTP Klasse
     *
     * @var Smtp
     */
    private $smtp;


    /**
     * @var $mysql MySql
     */
    public $mysql;

    /**
     * @var $json_response Response
     */
    public $json_response;
    /**
     * Initialisierung von wbs
     * @param string $root_path
     */
    public function __construct($root_path = null)
    {

        if ($root_path) {
            $this->setRootPath($root_path);
        }
        PHP_VERSION >= 5.6 or die('mdFramework requires PHP 5.6+');

        $this->loadEnv(true);
        $this->url_absolute = $this->env(ENV::URL_ABSOLUTE);
        $this->setErrorHandling();
    }

    /**
     * Aktuelle Version des wbs Frameworks aus Composer anzeigen
     *
     * @return string
     */
    public function getMdFVersion(){

        $mdf_version = 'Unknown Version';
        try{
            if(!class_exists('Composer\\InstalledVersions')){
                return 'Please update Composer';
            }
            $mdf_version =  InstalledVersions::getPrettyVersion('wbs/framework');
            return $mdf_version;

        }catch(\Exception $e){
            return $mdf_version. $e->getMessage();
        }

    }

    /**
     * Get the Configuration Parameter from .env Files,
     * Configuration  from .env.local overwrites
     */
    private function loadEnv($fail_if_missing = false)
    {
        $file_found = false;
        if (file_exists($this->getRootPath() . '.env')) {
            $this->dotenv()->load($this->getRootPath() . '.env');
            $file_found = true;
        }
        if (file_exists($this->getRootPath() . '.env.local')) {
            $this->dotenv()->load($this->getRootPath() . '.env.local');
            $file_found = true;
        }
        if((!$file_found) && $fail_if_missing ){
            die('FAIL: Environment Files not found in '.$this->getRootPath());
        }
    }

    /**
     * Parameter APP_ENV aus der ENV
     *
     * @return string
     */
    public function getAppEnv()
    {
        return $this->env(ENV::APP_ENV, 'dev');
    }
    /**************************************************************************
     *  H E L P E R _ F U N C T I O N S
     *************************************************************************/
    /**
     * Konfiguration für Javascript
     *
     * @return string
     */
    public function  getJsonConfig()
    {

        $md_config = [
            'APP_ENV' => $this->env('APP_ENV'),
            'URL_ABSOLUTE' => $this->env('URL_ABSOLUTE'),
            'ROOT_PATH' => $this->env('ROOT_PATH')
        ];

        return json_encode($md_config);
    }

    /**
     * Error Handling according to APP ENV
     */
    public function setErrorHandling()
    {

        switch ($this->getAppEnv()) {
            case ENV::APP_ENV_LIVE:
                error_reporting(E_ERROR);
                ini_set('display_errors', '0');
                ini_set('display_startup_errors', '0');
                break;
            case ENV::APP_ENV_DEV:
            default:
                /**
                 * We are in development Mode
                 */
                ini_set('display_errors', '1');
                ini_set('display_startup_errors', '1');
                error_reporting(E_ALL);
                break;
        }

    }

    /**
     * Einen Wert aus dem $_POST Array auf Existenz prüfen und zurückgeben, sonst $default
     *
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public function post($key, $default = '')
    {

        if (!array_key_exists($key, $_POST)) {
            return $default;
        }
        return $_POST[$key];
    }

    /**
     * Einen Wert aus dem $_GET Array auf Existenz prüfen und zurückgeben, sonst $default
     *
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public function get($key, $default = '')
    {

        if (!array_key_exists($key, $_GET)) {
            return $default;
        }
        return $_GET[$key];
    }

    /**
     * Einen Wert aus dem $_GET, $_POST, $_ENV Array auf Existenz prüfen und zurückgeben,
     * in der Reihenfolge
     *
     * sonst $default
     *
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public function in($key, $default = '')
    {

        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        if (array_key_exists($key, $_ENV)) {
            return $_ENV[$key];
        }

        return $default;
    }

    /**
     * Gibt POST unf GET als Array zurücl
     * @return array
     */
    public function inAll()
    {
        $response = array();
        $response['GET']    =   $_GET;
        $response['POST']   =   $_POST;
        return $response;
    }

    /**
     * Einen Wert aus dem $_ENV Array auf Existenz prüfen und zurückgeben, sonst $default
     *
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public function env($key, $default = '')
    {

        if (!array_key_exists($key, $_ENV)) {
            return $default;
        }
        return $_ENV[$key];
    }

    /**
     * Einen Wert aus einem Array auf Existenz prüfen und zurückgeben
     * Ansonsten default
     *
     * @param $arr
     * @param $key
     *
     * @return mixed Leerstring oder Value
     */
    public function getArrayValue($key, $arr,$default='')
    {
        if (array_key_exists(
            $key,
            (array)$arr
        )) {
            return $arr[$key];
        }

        return $default;
    }

    /**************************************************************************
     *  S U B C L A S S E S
     *************************************************************************/

    /**
     * API Controller
     *
     * @return \wbs\Framework\Api\ApiController
     */
    public function api()
    {

        if (is_null($this->api)) {
            $this->api = new ApiController($this);
        }
        return $this->api;
    }
    /**
     * Instance of Symfony Dotenv
     *
     * Quellcode: https://github.com/symfony/dotenv
     *
     * @return Dotenv
     */
    public function dotenv()
    {

        if (is_null($this->dotenv)) {
            $this->dotenv = new Dotenv();
        }
        return $this->dotenv;
    }

    /**
     * Instanz der Auftrag Klasse
     *
     * @return Auftrag
     *
     * @throws Exception
     */
    public function auftrag()
    {
        if (is_null($this->auftrag)) {
            $this->auftrag = new Auftrag($this);
        }
        return $this->auftrag;

    }

    /**
     * Instanz der wbsFactory Klasse
     *
     * @throws Exception
     */
    public function factory()
    {
        if (is_null($this->factory)) {
            $this->factory = new wbsFactory($this);
        }
        return $this->factory;

    }

    /**
     * @return Filter
     */
    public function filter()
    {
        if (is_null($this->filter)) {
            $this->filter = new Filter($this);
        }
        return $this->filter;

    }


    /**
     * Alias for Factory
     *
     * @return wbsFactory
     * @throws Exception
     */
    public function f()
    {
        return $this->factory();
    }

    /**
     * Instanz der Klasse Ip
     *
     * @return \wbs\Framework\Ip\Ip
     * @throws Exception
     */
    public function ip()
    {
        if(is_null($this->ip)){
            $this->ip = new Ip();
        }
        return $this->ip;
    }

    /**
     * @return LoggerInterface
     * @throws Exception
     * @throws Exception
     */
    public function log()
    {

        if (is_null($this->log)) {
            $filename = 'wbs-' . date("m-Y") . '.log';
            $this->log = new Log($this,$this->getLogPath() . $filename);
//            $this->log->info('wbs loaded log()');
        }
        return $this->log;

    }

    /**
     * @return \wbs\Framework\LogDB\LogDBController
     * @throws Exception
     */
    public function logDB()
    {
        if (is_null($this->log_db)) {
            $this->log_db = new LogDBController($this);
        }
        return $this->log_db;
    }

    /**
     * @return Doctrine
     * @throws Exception
     */
    public function doctrine()
    {

        if (is_null($this->doctrine)) {
            $this->doctrine = new Doctrine($this);
        }
        return $this->doctrine;

    }

    /**
     * Return Instance of Smtp Class
     *
     * @return Smtp
     */
    public function smtp()
    {
        if (is_null($this->smtp)) {
            $this->smtp = new Smtp($this);
        }
        return $this->smtp;
    }

    /**
     * Return Instance of Html Class
     *
     * @return \wbs\Framework\Html\Html
     * @throws \Exception
     */
    public function html()
    {
        if (is_null($this->html)) {
            $this->html = $this->factory()->getHtml();
        }
        return $this->html;
    }

    /**
     * Return Instance of Json Class
     *
     * @return \wbs\Framework\Json\Json
     * @throws \Exception
     */
    public function json()
    {
        if (is_null($this->json)) {
            $this->json = $this->factory()->getJson();
        }
        return $this->json;
    }

    /**
     * Return Instance of Json Class
     *
     * @return Response
     * @throws \Exception
     */
    public function JsonResponse()
    {
        if (is_null($this->json_response)) {
            $this->json_response = $this->factory()->getJsonResponse();
        }
        return $this->json_response;
    }

    /**
     * Return Instance of Smarty Wrapper
     *
     * @return \wbs\Framework\Smarty\SmartyWrapper
     * @throws \Exception
     */
    public function smarty()
    {
        if (is_null($this->smarty)) {
            $this->smarty = $this->factory()->getSmartyWrapper('');
            $this->smarty->smarty()->clearAllCache();
        }
        return $this->smarty;
    }


    /**************************************************************************
     *  G E T T E R / S E T T E R
     *************************************************************************/
    /**
     * Set the Root Path of the Project
     *
     * @param string $root_dir
     */
    public function setRootPath($root_dir)
    {
        $this->root_dir = rtrim(
                              $root_dir,
                              '/'
                          ) . '/';
    }

    /**
     * Root Dir of the Project
     *
     * @return string
     */
    public function getRootPath()
    {
        return realpath(
                   rtrim(
                       $this->root_dir,
                       '/'
                   )
               ) . '/';
    }

    /**
     * Public Dir of the Project
     *
     * @return string
     */
    public function getPublicPath()
    {
        return $this->getRootPath() . 'public/';
    }

    /**
     * Confog Dir of the Project
     *
     * @return string
     */
    public function getConfigPath()
    {
        return $this->getRootPath() . 'config/';
    }

    /**
     * Confog Dir of the Project
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->getRootPath() . 'templates/';
    }

    /**
     * Cache Path of the Project ( Symfony like)
     *
     * @return string
     */
    public function getCachePath()
    {
        return $this->getRootPath() . 'var/cache/';
    }

    /**
     * Cache Path of the Project ( Symfony like)
     *
     * @return string
     */
    public function getDataPath()
    {
        return $this->getRootPath() . 'var/data/';
    }

    /*
     * Log Path of the Project ( Symfony like)
     *
     * @return string
     */
    public function getLogPath()
    {
        return $this->getRootPath() . 'var/log/';
    }

    /*
     * Tmp Path of the Project ( Symfony like)
     * for temporary Files
     *
     * @return string
     */
    public function getTempPath()
    {
        return $this->getRootPath() . 'var/tmp/';
    }

    /**************************************************************************
     *  G E T T E R / S E T T E R
     *************************************************************************/
    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the Absolute URL of this Project
     *
     * @return string
     */
    public function getUrlAbsolute()
    {
        return $this->url_absolute;
    }

    public function getMySql()
    {
        if (is_null($this->mysql)) {
            $this->mysql = new MySql($this);
        }
        return $this->mysql;
    }

}
