<?php
namespace Library;

class Month
{


    public $unix_first_day_in_the_month;
    public $text_first_day_of_the_month;
    public $date_time_array;
    public $days_in_month_count;
    public $current_month_number;
    public $current_year;


    public function __construct($request)
    {
        $this->current_month_number=($request->post('chosen_month_number'))?$request->post('chosen_month_number'):date('m');

        $this->current_year=($request->post('chosen_year'))?$request->post('chosen_year'):date('Y');

        $this->unix_first_day_in_the_month = mktime(0, 0, 0,  $this->current_month_number, 1, $this->current_year);

        $this->date_time_array = getdate( $this->unix_first_day_in_the_month);

        $this->text_first_day_of_the_month=($this->date_time_array['weekday']);

        $this->days_in_month_count=cal_days_in_month(CAL_GREGORIAN, $this->current_month_number, $this->current_year);

    }

}



