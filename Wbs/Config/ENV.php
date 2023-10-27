<?php

namespace wbs\Framework\Config;


/**
 * WBSFW
 * Class ENV
 * Konstanten für den Zugriff auf die ENV
 */
class ENV
{

    const PROJECT_NAME = 'PROJECT_NAME';

    ### Path / Need Trailing Slash
    const URL_ABSOLUTE = 'URL_ABSOLUTE';
    const ROOT_PATH    = 'ROOT_PATH';

    ### Email of the Site Admin
    const SITE_ADMIN = 'SITE_ADMIN';

    ### App Environment
    const APP_ENV    = 'APP_ENV';
    const APP_SECRET = 'APP_SECRET';

    ### Mögliche ZUstände für App Env
    const APP_ENV_LIVE = 'live';
    const APP_ENV_DEV  = 'dev';

    ### MySQL Database ###
    const MYSQL_SERVER   = 'MYSQL_SERVER';
    const MYSQL_DATABASE = 'MYSQL_DATABASE';
    const MYSQL_USER     = 'MYSQL_USER';
    const MYSQL_PASSWORD = 'MYSQL_PASSWORD';
    const MYSQL_SALT     = 'MYSQL_SALT';

    const SMTP_HOST       = 'SMTP_HOST';
    const SMTP_PORT       = 'SMTP_PORT';
    const SMTP_USER       = 'SMTP_USER';
    const SMTP_PASS       = 'SMTP_PASS';
    const SMTP_FROM_EMAIL = 'SMTP_FROM_EMAIL';
    const SMTP_FROM_NAME  = 'SMTP_FROM_NAME';

    ###> Zugang und Konfiguration für die eigene API
    const API_SERVER_USER     = 'API_AUTH_USER';
    const API_SERVER_PASSWORD = 'API_AUTH_PASSWORD';
    const API_DIR             = 'API_DIR';

    /**
     *  Add this method to your class definition if you want an array of class constants
     * @return array
     */
    public function getClassConstants()
    {
        $reflect = new \ReflectionClass(get_class($this));
        return $reflect->getConstants();
    }

}