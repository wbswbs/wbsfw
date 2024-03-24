<?php

namespace wbs\Framework\Caching;
use wbs\Framework\Wbs;
use wbs\Framework\WbsClass;

/***************************************************************************************
 * @filesource  cache/cache.php
 ***************************************************************************************/

/* *****************************************************************************
 *             K L A S S E N D E F I N I T I O N
*******************************************************************************/

/**
 * Class cache
 *
 * @package de\blessen\wbsfw\core\cache
 */
class Cache extends WbsClass
{
    /**
     * @var string
     */
    protected $file_name;
    /**
     * @var string
     */
    protected $path;
    /**
     * @var string
     */
    protected $cache_name;


    /**
     * INIT
     *
     * @param string $file_name
     */
    public function __construct(Wbs $wbs,$cache_path="",$file_name = '')
    {
        parent::__construct($wbs);
        $this->cache_name = '';
        $this->file_name = $file_name;
        $this->path = $this->wbs()->getCachePath().'/'.$cache_path;
    }
    /***************************************************************************************
     *           F U N C T I O N S
     ***************************************************************************************/
    /**
     * Existenz prüfen
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->path.$this->file_name);
    }
    /**
     * Löschen
     */
    public function delete()
    {
        if (file_exists($this->path.$this->file_name)){
            unlink($this->path.$this->file_name);
            $this->wbs()->log()->info('Lösche Cache '.$this->path.$this->file_name);

        }
    }
    /**
     * Inhalt auslesen
     *
     * @return string|false The function returns the read data or false on failure.
     */
    public function read()
    {
        return file_get_contents($this->path.$this->file_name);
    }

    /**
     *
     * @param $content
     *
     * @throws \Exception
     */
    public function write($content)
    {
        try {

            if(!$this->file_name){
                throw new \Exception('No File Name given');
            }
            if(!is_dir($this->path)){
                mkdir( $this->path, 0777, true );
            }

            file_put_contents($this->path.$this->file_name, $content);
            $this->wbs()->log()->info('Writing Cache to '.$this->path.$this->file_name);


        } catch (\Exception $e) {
            throw new \RuntimeException('Cache Write: '.$e->getMessage());
        }

    }
    /**
     * return lastModified as Timestamp
     */
    public function getDate()
    {
        return filemtime($this->path.$this->file_name);
    }
    /**
     * Alle Einträge die mit folgendem Namen starten,
     * aus dem aktuellen Verzeichnis löschen
     * @param string $string
     */
//    public function deleteAllStartingWith($string){
//        $vz = $this->wbs()->factory()->getVerzeichnis();
//        $vz->removeFilesStartingWith($this->path, $string);
//        unset($vz);
//    }

    /**
     * Alle Einträge aus dem aktuellen Verzeichnis löschen
     *
     * @param string $empty_sub_directories
     *
     * @internal param string $string
     */
//    public function emptyCache($empty_sub_directories = 'false'){
//        $vz = $this->wbs->factory()->getVerzeichnis();
//        #shout($this->path);
//        $vz->removeFiles($this->path,$empty_sub_directories);
//        unset($vz);
//    }

    /**
     * Cache Verzeichnis erstellen
     *
     * @param      $path
     * @param bool $sub
     */
//    public function createCacheVZ($path,$sub =true){
//        $vz=$this->wbs->factory()->getVerzeichnis();
//        foreach(range(0, 9) as $number) {
//            $vz->createDirectory($path.$number.'/');
//            if($sub) {
//                $this->createCacheVZ(
//                    $path . $number . '/',
//                    false
//                );
//            }
//        }
//        foreach(range('a','z') as $buchstabe) {
//            $vz->createDirectory($path.$buchstabe.'/');
//            if($sub) {
//                $this->createCacheVZ(
//                    $path . $buchstabe . '/',
//                    false
//                );
//            }
//        }
//    }

    /**
     * Verzeichnis für das Cache ermitteln
     * a/b/ für abicall_987
     *
     * @param $str
     *
     * @return string
     */
    public static function get2LetterPath($str){
        $path = '';
        $str = strtolower($str);
        switch (strlen($str)){
            case 1:
                if(preg_match('#[a-z0-9]#',$str[0])) {
                    $path .= $str[0] . '/';
                }
                break;
            case 2:
                if(preg_match('#[a-z0-9]#',$str[0])) {
                    $path .= $str[0] . '/';
                }
                if(preg_match('#[a-z0-9]#',$str[1])) {
                    $path .= $str[1] . '/';
                }
                break;
            case 0:
            default:
        }
        return $path;
    }

    /**
     * Verzeichnis für das Cache ermitteln
     * a/ für abicall_987
     *
     * @param $str
     *
     * @return string
     */
    public static function get1LetterPath($str){
        $path = '';
        $str = strtolower($str);
        if(preg_match('#[a-z0-9]#',$str[0])) {
            $path .= $str[0] . '/';
        }
        return $path;
    }
    /***************************************************************************************
     *             G E T T E R / S E T T E R
     ***************************************************************************************/
    /**
     * Ordnerformat so wie 'artikel/'
     *
     * @param $path
     *
     * @internal param string $path
     */
    public function setBasePath($path)
    {
        $this->path = $path;
        $this->path = $this->path.$this->path;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->path;
    }
    /**
     * Ordnerformat so wie 'artikel/'
     * Ordner muss existieren
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path= $path;
        $this->path = $this->path.$this->path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Old Synonym
     *
     * @deprecated
     * @param string $value
     */
    public function setName($value)
    {
        $this->setFileName($value);
    }

    /**
     * Old Synonym
     *
     * @return string
     *
     * @deprecated
     */
    public function getName()
    {
        return $this->getFileName();
    }
    /**
     * @param string $value
     */
    public function setFileName($value)
    {
        $this->file_name= $value;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }
    /**
     * @param  string $value
     */
    public function setCacheName($value)
    {
        $this->cache_name= $value;
    }

    /**
     * @return string
     */
    public function getCacheName()
    {
        return $this->cache_name;
    }

}