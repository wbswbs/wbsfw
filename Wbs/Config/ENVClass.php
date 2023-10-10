<?php

namespace wbs\Framework\Config;

/**
 * Empty Class, just do access the ENV Constants in a foreach
 * @deprecated
 */
class ENVClass extends ENV
{

    /**
     *  Add this method to your class definition if you want an array of class constants
     * @return array
     * @deprecated Use ENV Class
     */
    public function getClassConstants()
    {
        $reflect = new \ReflectionClass(get_class($this));
        return $reflect->getConstants();
    }
}