<?php

namespace wbs\Framework\Smarty;


use wbs\Framework\Config\ENV;
use wbs\Framework\Wbs;
use wbs\Framework\WbsClass;
use Smarty;
use SmartyException;

/**
 * Class SmartyWrapper
 *
 * Laden dieser Klasse über
 *
 *    $tpl = $md->factory()->getSmartyWrapper(
 *       (string)$station->getTemplate()
 *       );
 *
 *
 * @package wbs\Framework\Smarty
 */
class SmartyWrapper extends WbsClass
{
    /**
     * @var Smarty
     */
    var $smarty;
    /**
     * @var bool
     */
    var $caching;

    /**
     * @var string
     */
    private $template_path;
    /**
     * @var string
     */
    private $template_cache_path;
    /**
     * @var string
     */
    private $template_compile_path;
    /**
     * @var string
     */
    private $template_folder;

    /**
     * SmartyWrapper constructor.
     * @param $md
     * @param string $template_path
     * @param string $template_cache_path
     * @param string $template_compile_path
     * @param string $template_folder The Folder in the Template Path for special Layouts
     * @throws \SmartyException
     */
    public function __construct($md,
                                $template_path,
                                $template_cache_path = '',
                                $template_compile_path = '',
                                $template_folder = '')
    {
        parent::__construct($md);
        $this->template_path = $template_path;
        $this->template_cache_path = (string)$template_cache_path;
        $this->template_compile_path = (string)$template_compile_path;
        $this->template_folder = (string)$template_folder;
        $this->caching = false;

        //$this->checkWartungsModus($md);
    }

    /**
     * Disable Caching in Developmant Mode
     *
     * @param bool $dev_mode
     */
    public function setDevelopmentMode($dev_mode = true)
    {
        $this->caching = !$dev_mode;
    }

    /**
     * Factory Function to get the Smarty Object
     *
     * @return Smarty
     * @throws \Exception
     * @throws \Exception
     */
    public function smarty()
    {
        if (is_null($this->smarty)) {
            $this->smarty = new Smarty();
//            $this->smarty->force_cache = false;
            $this->smarty->caching = $this->caching;
            $this->smarty->setTemplateDir($this->template_path);
            if ($this->template_cache_path) {
//                $this->md()->log()->info('Smarty Cache Pfad: ' . $this->template_cache_path);
                $this->smarty->setCacheDir($this->template_cache_path);
            }
            if ($this->template_compile_path) {
//                $this->md()->log()->info('Smarty Compile Pfad: ' . $this->template_compile_path);
                $this->smarty->setCompileDir($this->template_compile_path);
            }
        }
        return $this->smarty;
    }

    /**
     * Notices in Smarty nicht anzeigen
     *
     * @throws \Exception
     */
    public function suppressNotices()
    {
        $this->smarty()->setErrorReporting(E_ALL & ~E_NOTICE);
        $this->smarty()->muteUndefinedOrNullWarnings();
    }

    /**
     * Notices in Smarty anzeigen
     *
     * @throws \Exception
     */
    public function showNotices()
    {
        $this->smarty()->setErrorReporting(E_ALL);
    }

    /**
     * @param null $template
     * @param array $assign
     * @param false $caching
     *
     * @return string
     *
     * @throws SmartyException
     */
    public function fetchTemplate($template, array $assign = array(), $caching = false)
    {

        if ($template) {
            setlocale(LC_ALL, array('de_DE.utf8', 'de_DE', 'de'));
//            $this->md()->log()->add('Caching = ' . $this->caching);
            $this->smarty()->setCaching($this->caching);

            foreach ($assign as $key => $variable) {
                $this->smarty()->assign($key, $variable);
            }

            return $this->smarty()->fetch($this->template_path . $this->template_folder . '/' . $template);

        }
        return 'No Template given';
    }

    /**
     *
     *  EIn Template aus dem absoluten Pfad rendern
     * @param $template_path
     * @param array $assign
     * @param false $caching
     *
     * @return string
     *
     * @throws \SmartyException
     */
    public function fetchAbsoluteTemplate($template_path, $assign = array(), $caching = false)
    {

        setlocale(LC_ALL, array('de_DE.utf8', 'de_DE', 'de'));
//            $this->md()->log()->add('Caching = ' . $caching);
        $this->smarty()->setCaching($caching);

//            var_dump($template_path);
//            var_dump($assign);
//
        foreach ($assign as $key => $variable) {
            $this->smarty()->assign($key, $variable);
        }

        return $this->smarty()->fetch($template_path);

    }

    /**
     * @param null $template
     * @param array $assign
     * @param false $caching
     *
     * @return void
     *
     * @throws SmartyException
     */
    public function getTemplate($template = null, array $assign = array(), $caching = false)
    {

        if ($template) {
            setlocale(LC_ALL, array('de_DE.utf8', 'de_DE', 'de'));
//            $this->md()->log()->add('Caching = ' . $this->caching);
            $this->smarty()->setCaching($this->caching);

            foreach ($assign as $key => $variable) {
                $this->smarty()->assign($key, $variable);
            }

            $this->smarty()->display($this->template_path . $this->template_folder . '/' . $template);
//            if(!$this->caching){
//                $this->smarty->clearAllCache();
//            }
            exit();
        }
    }

    /**
     * @param $md Wbs
     * @throws SmartyException
     */
    private function checkWartungsModus($md)
    {
        $devIps = explode(',', $md->env(ENV::EXCLUDE_WARTUNGS_MODUS));
        if ($md->env(ENV::WARTUNGS_MODUS) === "true" && !in_array($md->factory()->getStation()->calculateIP(), $devIps)) {
            $this->suppressNotices();
            $this->smarty()->display($this->template_path . '/default/wartung.tpl');
            $this->smarty->clearAllCache();
            exit();
        }
    }

    /**
     * @param $message
     * @throws SmartyException
     */
    public function sendError($message)
    {
        $this->suppressNotices();
        $this->smarty()->setCaching(false);
        $this->smarty()->assign('message', $message);
        $this->smarty()->assign('station_name', '');
        $this->smarty()->display($this->template_path . '/default/error.tpl');
        exit();
    }

    /**
     * @return string
     */
    public function getTemplateFolder()
    {
        return $this->template_folder;
    }

    /**
     * @param $template_folder
     */
    public function setTemplateFolder($template_folder)
    {
        $this->template_folder = $template_folder;
    }


}
