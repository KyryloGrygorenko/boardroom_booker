<?php

namespace Library;

class NextAndPreviousMonth
{
    public $year_of_previous_month='';
    public $year_of_next_month='';
    public $previous_month='';
    public $next_month='';


    public function __construct($current_month_number,$current_year)
    {
        $this->setNextMonth($current_month_number,$current_year);
        $this->setPreviousMonth($current_month_number,$current_year);
    }

    public function setNextMonth($current_month_number,$current_year)
    {
            if ($current_month_number<12){
                $this->next_month = $current_month_number+1;
                $this->year_of_next_month=$current_year;
            }elseif($current_year == END_YEAR) {
                $this->next_month = false;
            }else{
                $this->next_month = 1;
                $this->year_of_next_month=$current_year+1;
            }

    }


    public function setPreviousMonth($current_month_number,$current_year)
    {
        if ($current_month_number > 1) {
            $this->previous_month = $current_month_number - 1;
            $this->year_of_previous_month = $current_year;
        } elseif($current_year == START_YEAR) {
            $this->previous_month = false;
        }else {
            $this->previous_month = 12;
            $this->year_of_previous_month = $current_year - 1;
        }


    }
}