<?php

namespace wbs\Framework\Api;

use Exception;
use wbs\Framework\Config\ENV;
use wbs\Framework\Curl\Curl;
use wbs\Framework\WbsClass;

/**
 * Class Client
 *
 * Anfragen an die wbs API senden
 */
class Client extends WbsClass{


    /**
     * @var \wbs\Framework\Curl\Curl $curl
     */
    private $curl;


    /**
     * Eine Anfrage an die wbs API via DELETE senden
     *
     * @param string $request Should start with /api
     * @return string
     * @throws \Exception
     */
    public function delete($request,$data)
    {
        $this->curl = $this->getCurl($request);
        $this->curl->setCustomRequest("DELETE");
        $this->curl->setPost(json_encode($data));
        $this->wbs->log()->info("[DELETE] CURL REQUEST:" . $request);
        return $this->sendCurl();
    }

    /**
     * Eine Anfrage an die wbs API via PUT senden
     *
     * @param string $request Should start with /api
     * @return string
     * @throws \Exception
     */
    public function put($request,$data)
    {
        $this->curl = $this->getCurl($request);
        $this->curl->setCustomRequest("PUT");
        $this->curl->setPost(json_encode($data));
        $this->wbs->log()->info("[PUT] CURL REQUEST: {$request} Data: " . json_encode($data));
        return $this->sendCurl();
    }

    /**
     * Eine Anfrage an die wbs API via POST mit Upload
     * Übergabe via file_contents
     * Empfang $_FILES['file_contents']
     * @param $request
     * @param $file_name
     * @param $file_name_with_full_path
     * @return string
     * @throws Exception
     */
    public function postFile($request,$file_name,$file_name_with_full_path)
    {
        $data= [];

        if (!empty($file_name_with_full_path) && is_file($file_name_with_full_path)){
            $mime_type = mime_content_type($file_name_with_full_path);
            $data['file_contents'] = new \CURLFile($file_name_with_full_path,$mime_type,$file_name);
            $data['file_name'] = $file_name;
        }

        $this->curl = $this->getCurl($request);
        $this->curl->setCustomRequest("POST");
        $this->curl->setPost($data);
        $this->wbs->log()->info("[POST FILE] CURL REQUEST:" . $request);
        return $this->sendCurl();
    }

    /**
     * Eine Anfrage an die wbs API via POST senden
     *
     * @param string $request
     * @param $data
     * @return string
     * @throws \Exception
     */
    public function post($request,$data)
    {
        $this->curl = $this->getCurl($request);
        $this->curl->setCustomRequest("POST");
        $this->curl->setPost(json_encode($data));
        $this->wbs->log()->info("[POST] CURL REQUEST:" . $request);
        return $this->sendCurl();
    }



    /**
     * GetRequest with success status and error message
     * @param $request
     * @return array
     */
    public function getWithSuccess($request)
    {
        try {
            $this->curl = $this->getCurl($request);
            $this->wbs->log()->info("[GET WITH SUCCESS] CURL REQUEST:" . $request);
            $response = $this->sendCurlWithException();
        }catch (Exception $e){
            return
                array(
                "success" => false,
                "message"=> $e->getMessage(),
                "data"=>null
                );
        }
        return
            array(
                "success" => true,
                "message"=>"",
                "data"=>$response
                );

    }

    /**
     * Eine Anfrage an die wbs API via GET senden
     * @param $request
     * @return string
     * @throws \Exception
     */
    public function get($request)
    {
        $this->curl = $this->getCurl($request);
        $this->wbs->log()->info("[GET] CURL REQUEST:" . $request);
        return $this->sendCurl();
    }

    /**
     * returns CURL Object
     * @param string $request
     * @return Curl
     */
    private function getCurl($request)
    {

        $url = $this->wbs()->env(ENV::API_SERVER_URL) . '/' .
            $this->wbs()->env(ENV::API_SERVER_DIR) . '/' . $request;

        $curl = new Curl(
            $url
        );
        $curl->useAuth(true);
        $curl->setName($this->wbs()->env(ENV::API_SERVER_USER));
        $curl->setPass($this->wbs()->env(ENV::API_SERVER_PASSWORD));

        return $curl;

    }

    /**
     * @throws \Exception
     */
    private function sendCurlWithException()
    {
        $this->curl->createCurl();
        if($this->curl->getErrno()){
            $this->wbs()->log()->error(
                __FUNCTION__.' Curl Error: ' .$this->curl->getErrno()."\n\n".
                $this->curl->getUrl(),
                $this->curl->getPost()
            );
            throw new Exception($this->curl->getErrno(),500);
        }
        $data = $this->curl->getResponse();
        /**
         * 200 I.O.
         * 403 Permission denied
         * 404 Falsche Parameter
         */
        $http_status = $this->curl->getHttpStatus();

        switch($http_status){
            case 403:
                throw new Exception('403 Permission denied',$http_status);
            case 404:
            case 500:
                throw new Exception($this->curl->getResponse(),$http_status);
            case 200:
            default:
                return $data;
        }

    }


    /**
     * @throws \Exception
     */
    private function sendCurl()
    {
        $this->curl->createCurl();
        if($this->curl->getErrno()){
            $this->wbs()->log()->error(
                __FUNCTION__.' Curl Error: ' .$this->curl->getErrno()."\n\n".
                $this->curl->getUrl(),
                $this->curl->getPost()
            );
            throw new Exception($this->curl->getErrno(),500);
        }
        $data = $this->curl->getResponse();
        /**
         * 200 I.O.
         * 403 Permission denied
         * 404 Falsche Parameter
         */
        $http_status = $this->curl->getHttpStatus();

        switch($http_status){
            case 403:
                return '403 Permission denied';
            case 200:
            case 404:
            default:
                return $data;
        }

    }

    /**
     * Json Daten schön hübsch in einer Tabelle darstellen
     *
     * @param string $jsonText
     * @return string
     */
    public static function jsonToDebug($jsonText = '')
    {
        $arr = json_decode($jsonText, true);
        $html = "";
        if ($arr && is_array($arr)) {
            $html .= self::_arrayToHtmlTableRecursive($arr,1);
        }
        return $html;
    }

    /**
     * @param $arr
     * @param $level
     * @return string
     */
    private static function _arrayToHtmlTableRecursive($arr,$level) {
        $class='';
        if($level===1){
            $class="table table-striped table-hover";
        }

        $str = "<table class='{$class}'><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td>$key</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (!empty($val)) {
                    $str .= self::_arrayToHtmlTableRecursive($val,$level+1);
                }
            } else {
                $str .= "<strong>$val</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";

        return $str;
    }
}