<?php

namespace wbs\Framework;


use wbs\Framework\Wbs;

/**
 * Class wbs, Blaupause für wbs Klassen
 **/
class WbsClass
{
    /**
     * @var Wbs
     */
    protected $wbs;

    /**
     * WbsClass constructor.
     *
     * @param wbs $wbs
     */
    public function __construct(wbs $wbs)
    {
        $this->wbs = $wbs;
    }

    /**
     * @return wbs
     */
    public function wbs()
    {
        return $this->wbs;
    }
    /**
     * @return \wbs\Framework\Html\Html
     */
    public function html()
    {
        return $this->wbs()->html();
    }
    /**
     * @return \wbs\Framework\Html\Bootstrap\Bootstrap
     */
    public function bootstrap()
    {
        return $this->wbs()->html()->bootstrap();
    }
    /**
     * @return \wbs\Framework\Mail\Smtp
     */
    public function mailer()
    {
        return $this->wbs()->smtp();
    }
}
