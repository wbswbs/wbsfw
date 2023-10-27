<?php
namespace wbs\Framework\Ip;

class Ip{

    /**
     * IP Adresse der Anfrage
     *
     * @return string
     */
    public static function calculateIP()
    {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return  $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return  $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return  $_SERVER['REMOTE_ADDR'];
        }
        return '';

    }

}