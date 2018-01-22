<?php
namespace Library;

abstract class AllYearsRange
{

    
    public static function GetAllYearsFromTo($start_year,$latest_year)
    {

        $year=$start_year;
        while($year<=$latest_year){
            $all_years_in_range[]=$year;
            $year++;
        }
        return $all_years_in_range;
    }
    

}