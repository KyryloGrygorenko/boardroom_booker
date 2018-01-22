<?php
namespace Library;

abstract class GetHoursAndMinutesArray
{



    public static function getHoursArray(){
        $hours=[];
        for($i=1; $i <= 12; $i++){
            $hours[]=$i;
        }
        return $hours;
    }


    public static function getMinutesArray()
    {
        $minutes = [];
        for ($i = 0; $i <= 60; $i++) {
            if ($i < 10) {
                $minutes[] = '0' . $i;
            } else {
                $minutes[] = $i;
            }
        }
        return $minutes;
    }
}