<?php

namespace wbs\Framework\Log;

use wbs\Framework\Json\Response;
use wbs\Framework\WbsClass;

/**
 * Class LogController
 * @package wbs\Framework\Log
 */
class LogController extends WbsClass{

    /**
     * @var \wbs\Framework\Log\Log
     */
    protected $log;

    const ACTION_ERROR = 'error';
    const ACTION_INFO = 'info';

    /**
     * Handle the Ajax Request and return a JSON formatted Response
     */
    public function handleAjaxRequest($action)
    {
        $response = new Response();
        $response->setQuery(__CLASS__.' # '. __FUNCTION__);
        $message = 'JS '.$this->md()->in('message');
        $context = $this->md()->in('context');

        switch($action){
            case self::ACTION_ERROR:
                $this->log()->error($message,(array)$context);
                $response->setResult($message);
                $response->setSuccess(true);
                break;
            case self::ACTION_INFO:
                $this->log()->info($message,(array)$context);
                $response->setResult($message);
                $response->setSuccess(true);
                break;
            default:
                $response->setSuccess(false);
                $response->setMessage('Der ActionHandler '.$action. ' ist nicht vorhanden.');
        }
        return $response;
    }


    function log(){
        return $this->md()->log();
    }
}