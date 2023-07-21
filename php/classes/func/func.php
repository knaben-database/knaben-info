<?php

declare(strict_types=1);

require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/func/ip.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/func/colors.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/func/statusCodes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/php/classes/func/math.php');

class Func
{
    public static Ip $ip;
    public static Colors $colors;
    public static Math $math;
    public static StatusCodes $statusCodes;

    // Itself
    public static Func $instance;
    public static function init()
    {
        // Check is $_instance has been set
        if(!isset(self::$instance))
        {
            self::$ip = new Ip();
            self::$colors = new Colors();
            self::$statusCodes = new StatusCodes();
            self::$math = new Math();

            // Itself
            self::$instance = new Func();
        }

        // Returns the instance
        return self::$instance;
    }
}