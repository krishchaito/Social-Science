<?php
/**
 * Created by PhpStorm.
 * User: vyeddula
 * Date: 4/8/14
 * Time: 10:23 AM
 */

/**
 * Class Tm_Atrizine
 */
class Tm_Atrizine
{
    /**
     * @param $varName
     * @param null $default
     * @return null|string
     */
    static public function GetValue(&$varName, $default = null)
    {
        return isset($varName) ? trim($varName) : $default;
    }

    static public function GetRequestScheme()
    {
        if(!empty($_SERVER['REQUEST_SCHEME'])) {
            return $_SERVER['REQUEST_SCHEME'];
        }

        return 'http';
    }
}