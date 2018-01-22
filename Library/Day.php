<?php
namespace Library;

class Day
{


    public $current_month_number;
    public $current_year;
    public $current_day;
    public $current_day_unix;
    public $next_day_unix;
    public $all_appointments_objects;



    public function __construct($current_month_number,$current_year,$current_day)
    {
        $this->current_month_number=$current_month_number;
        $this->current_year=$current_year;
        $this->current_day=$current_day;
        $this->current_day_unix=mktime(0, 0, 0, $current_month_number, $current_day, $current_year);
        $this->next_day_unix=strtotime('+1 day', $this->current_day_unix);

    }



}



