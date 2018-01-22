<?php
namespace Library;

abstract class AmPmOrMilitaryTimeMode
{




    public static function getAmPmOrMilitaryTimeMode($request)
    {

        if($am_pm_or_military_hours_mode = $request->post('am_pm_or_military_hours_mode')){
        setcookie('am_pm_or_military_hours_mode', $am_pm_or_military_hours_mode, time() + 60 * 60 * 24 * 366, '/');
    }elseif (isset($_COOKIE['am_pm_or_military_hours_mode'])){
        $am_pm_or_military_hours_mode=$_COOKIE['am_pm_or_military_hours_mode'];
    }else{
        $am_pm_or_military_hours_mode='am_pm_mode';
    }

        return $am_pm_or_military_hours_mode;
    }
}