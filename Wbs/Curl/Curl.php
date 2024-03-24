<?php

namespace wbs\Framework\Curl;


/**
 * Class Curl
 *
 * @package wbs\Framework\Curl
 */
class Curl
{
    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    protected $_url;
    protected $_errno ='';
    protected $_followlocation;
    protected $_timeout;
    protected $_maxRedirects;
    protected $_cookieFileLocation = './cookie.txt';
    protected $_post;
    protected $_postFields;
    protected $_referer = "https://www.blessens.de";
    protected $_customRequest = false;
    protected $_session;
    protected $_webpage;
    protected $_includeHeader;
    protected $_noBody;
    protected $_status;
    protected $_binaryTransfer;
    protected $_header;
    protected $_progress = false;

    /**************************************************************************
     * RESPONSE Information
     *************************************************************************/
    protected $response_content_type='';
    protected $response_request_size=0;
    protected $response_http_code =0;
    protected $response_size_download='';
    protected $response_header_line=[];

    /**************************************************************************
     * Authentifizierung
     *************************************************************************/
    public $authentication = 0;
    public $auth_name = '';
    public $auth_pass = '';

    /**************************************************************************
     * Sicherheit
     *************************************************************************/
    protected bool $ignore_ssl = true;

    public function __construct($url, $followlocation = true, $timeOut = 60, $maxRedirecs = 4, $binaryTransfer = false, $includeHeader = false, $noBody = false)
    {
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;

        $this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
        $this->_header =  array(
            "REMOTE_ADDR: ". $this->getArrayValue('REMOTE_ADDR',$_SERVER),
            "X_FORWARDED_FOR: ".$this->getArrayValue('REMOTE_ADDR',$_SERVER)
        );
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
    private function getArrayValue($key, $arr,$default='')
    {
        if (array_key_exists(
            $key,
            (array)$arr
        )) {
            return $arr[$key];
        }

        return $default;
    }

    public function setReferer($referer)
    {
        $this->_referer = $referer;
    }

    public function setCookiFileLocation($path)
    {
        $this->_cookieFileLocation = $path;
    }

    public function setProgress()
    {
        $this->_progress = true;
    }

    public function setPost($postFields)
    {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    public function getPost()
    {
        return (array) $this->_postFields;
    }

    public function setCustomRequest($request)
    {
        $this->_customRequest = $request;
    }

    public function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
    }

    public function setTimeout($timeout)
    {
        $this->_timeout = (int)$timeout;
    }

    public function setBinaryTransfer()
    {
        $this->_binaryTransfer = true;
    }

    public function HandleHeaderLine( $curl, $header_line ) {
        $this->response_header_line[] = $header_line; // or do whatever
        return strlen($header_line);
    }

    public function createCurl($read_response_header=false)
    {
        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $this->_url);
        curl_setopt($s, CURLOPT_HTTPHEADER,  $this->_header);
        curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
        curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
        curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
        if($this->ignore_ssl){
            curl_setopt($s, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 0);
        }
        if ($this->authentication == 1) {
            curl_setopt($s, CURLOPT_USERPWD, $this->auth_name . ':' . $this->auth_pass);
        }
        if ($this->_post) {
            curl_setopt($s, CURLOPT_POST, true);
            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);

        }

        if($this->_progress){
            curl_setopt($s, CURLOPT_PROGRESSFUNCTION, 'self::progress');
            curl_setopt($s, CURLOPT_NOPROGRESS, false); // needed to make progress function work
        }

        if ($this->_includeHeader) {
            curl_setopt($s, CURLOPT_HEADER,true);
        }

        if ($this->_noBody) {
            curl_setopt($s, CURLOPT_NOBODY, true);
        }

        if ($this->_customRequest !== false) {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_customRequest);
        }

        if ($this->_binaryTransfer !== false) {
            curl_setopt($s, CURLOPT_SAFE_UPLOAD, false);
        }
        /*
        if($this->_binary)
        {
            curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
        }
        */
        curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
        curl_setopt($s, CURLOPT_REFERER, $this->_referer);

        $this->response_header_line=[];

        if($read_response_header) {
            // Catch Headers
            curl_setopt(
                $s,
                CURLOPT_HEADERFUNCTION,
                array($this, 'HandleHeaderLine')
            );
        }
        
        $this->_webpage = curl_exec($s);

        if(curl_error($s)){
            $this->_errno = curl_error($s);
        }
        $this->_status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        /**************************************************************************
         * REQUEST INFO
         *************************************************************************/
        $this->response_content_type = curl_getinfo($s, CURLINFO_CONTENT_TYPE);
        $this->response_request_size = curl_getinfo($s, CURLINFO_REQUEST_SIZE);
        $this->response_http_code = curl_getinfo($s, CURLINFO_HTTP_CODE);
        $this->response_size_download = curl_getinfo($s, CURLINFO_SIZE_DOWNLOAD);

        curl_close($s);

        if($this->_progress) {
            ob_flush();
            flush();
        }

    }

    public function getUrl(){
        return $this->_url;
    }
    public function useAuth($use)
    {
        $this->authentication = 0;
        if ($use == true) $this->authentication = 1;
    }

    public function setName($name)
    {
        $this->auth_name = $name;
    }

    public function setHeader($header)
    {
        $this->_header = array_merge($this->_header,$header);
    }

    public function getHeader()
    {
        return $this->_header;
    }


    public function setPass($pass)
    {
        $this->auth_pass = $pass;
    }

    public function getResponse()
    {
        return $this->_webpage;
    }

    public function getHttpStatus()
    {
        return $this->_status;
    }

    public function getErrno()
    {
        return $this->_errno;
    }


    /**
     * @return string
     */
    public function _tostring()
    {
        return (string)$this->_webpage;
    }

    private function progress($resource,$download_size, $downloaded, $upload_size, $uploaded)
    {
        $total=0;
        if($uploaded > 0 && $total < 100) {
            $total = (int)($uploaded / $upload_size * 100);
            echo "<div class='progress' style='z-index:{$total}'>".$total."%</div>";
            ob_flush();
        }
        flush();
        sleep(1); // just to see effect
    }

    public function getResponseContentType(): string
    {
        return $this->response_content_type;
    }

    public function getResponseRequestSize(): int
    {
        return $this->response_request_size;
    }

    public function getResponseHttpCode(): int
    {
        return $this->response_http_code;
    }

    public function getResponseSizeDownload(): string
    {
        return $this->response_size_download;
    }

    public function getResponseHeaderLine(): array
    {
        return $this->response_header_line;
    }

    public function isIgnoreSsl(): bool
    {
        return $this->ignore_ssl;
    }

    public function setIgnoreSsl(bool $ignore_ssl): void
    {
        $this->ignore_ssl = $ignore_ssl;
    }
    
}
