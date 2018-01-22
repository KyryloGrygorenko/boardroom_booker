<?php
namespace Library;

abstract class WeeksOfTheMonth
{


    public static $Monday='';
    public static $all_unix_days_of_the_month=[];
    public static $days_objects_by_week=[];


    public static function GetWeeksOfTheMonth($days_in_month,$days_mode,$first_day_of_the_month,$unix_day,$current_month_number,$current_year){

        $weeks=$days_in_month/7;
        $weeks_count=(int)ceil($weeks);
        $weeks_of_the_month=[];
        $unix_first_days[]=strtotime('+1 day', $unix_day);
        $unix_days=[];
        for ($i = 1; $i <= $weeks_count; $i++) {

            foreach ($days_mode as $day){

                $increment = isset($increment) && $increment <= 7 ? $increment : 1;

                if($day == $first_day_of_the_month || (isset($current_day) && $current_day>1) ){
                    $current_day=isset($current_day)?$current_day:1;

                    if($current_day <= $days_in_month){
                        $weeks_of_the_month[$i][$increment]=$current_day;
                        $object_day=new Day($current_month_number,$current_year,$current_day);
                        self::$days_objects_by_week[$current_day]=$object_day;


//                        $weeks_of_the_month->setUnixDate(strtotime('+1 day', $unix_day));;
//                        $unix_day=strtotime('+1 day', $unix_day);
                        $current_day++;
                    }else{
                        $weeks_of_the_month[$i][$increment] = "";
                    }

                }else{
                    $weeks_of_the_month[$i][$increment] = "";
                }

                if( $increment == 7 && $i == $weeks_count
                    && $weeks_of_the_month[$i][$increment]<$days_in_month
                    && $weeks_of_the_month[$i][$increment] != "") {

                    $weeks_count++;
                }

                $increment++;
            }

        }

        return $weeks_of_the_month;
    }

}