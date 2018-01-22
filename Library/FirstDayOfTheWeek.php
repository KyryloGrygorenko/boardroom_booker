<?php
namespace Library;

abstract class FirstDayOfTheWeek
{
    public static $Sunday=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    public static $Monday=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];


    public static function GetFirstDayMode($request){
        if($request->post('days_mode')){
            $days_mode_from_request=$request->post('days_mode');
            setcookie('days_mode', self::$$days_mode_from_request[0], time() + 60 * 60 * 24 * 366, '/');
            return self::$$days_mode_from_request;

        }elseif (!empty($_COOKIE['days_mode'])) {
            $days_mode_from_cookies=$_COOKIE['days_mode'];
            setcookie('days_mode', self::$$days_mode_from_cookies[0], time() + 60 * 60 * 24 * 366, '/');
            return self::$$days_mode_from_cookies;
        }
        return self::$Monday;
    }
}





