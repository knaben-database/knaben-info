<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/func/ip.php');

class Func
{
    public static Ip $ip;

    // Itself
    public static Func $instance;
    public static function init()
    {
        // Check is $_instance has been set
        if(!isset(self::$instance))
        {
            self::$ip = new Ip();

            // Itself
            self::$instance = new Func();
        }

        // Returns the instance
        return self::$instance;
    }
}