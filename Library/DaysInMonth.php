<?php
namespace Library;

abstract class DaysInMonth
{
//DEPRICATED
//SHCEDULED TO BE DELETED
    
    public static function HowManyDaysInMonth($unix_first_day_in_the_month)
    {
// Какой месяц
        $this_month = strftime ('%m', $unix_first_day_in_the_month);
// Первый день текущего месяца в формате timestamp
        $day = mktime(0, 0, 0, $this_month, 1);

// Первый день следующего месяца в формате timestamp
        $month_end = mktime(0, 0, 0, $this_month+1, 1);
        $days_in_month_count='';
        while ($day < $month_end)
        {
//            print strftime('%d', $day) . "<br>";
            $day += 86400;
            $days_in_month_count++;
        }

        return $days_in_month_count;
    }
    

}