<?php
namespace Library;

abstract class TimeIncrementParameter
{



    public static function getTimeIncrementParameter($if_event_reccuring_how_often){

            if($if_event_reccuring_how_often=='weekly'){
                return '+7 day';
            }
            if($if_event_reccuring_how_often=='bi-weekly'){
                return '+14 day';
            }
            if($if_event_reccuring_how_often=='monthly'){
                return '+1 month';
            }
    }


}