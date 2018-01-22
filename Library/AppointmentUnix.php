<?php
namespace Library;

class AppointmentUnix
{


    public $appointment_start_time_unix;
    public $appointment_end_time_unix;



    public function __construct($request)
    {
        $this->appointment_start_time_unix=$this->string_start_date_to_unix($request);
        $this->appointment_end_time_unix=$this->string_end_date_to_unix($request);
    }



    public function string_start_date_to_unix($request){
            if($appointment_date=$request->post('appointment_date')) {
                ;
                $appointment_date = explode('-', $appointment_date);
                $appointment_date_year = $appointment_date[0];
                $appointment_date_month = $appointment_date[1];
                $appointment_date_day = $appointment_date[2];
                $appointment_start_hour = $request->post('appointment_start_hour');
                $am_or_pm=$request->post('start_AM_PM');
                $appointment_start_hour = $this->transfer_am_pm_format_to_military_format($appointment_start_hour,$am_or_pm);
                $appointment_start_minute = $request->post('appointment_start_minutes');
                return mktime($appointment_start_hour, $appointment_start_minute, 0, $appointment_date_month, $appointment_date_day, $appointment_date_year);
            }
    }

    public function string_end_date_to_unix($request){
        if($appointment_date = $request->post('appointment_date')) {
            ;
            $appointment_date = explode('-', $appointment_date);
            $appointment_date_year = $appointment_date[0];
            $appointment_date_month = $appointment_date[1];
            $appointment_date_day = $appointment_date[2];
            $appointment_end_hour = $request->post('appointment_end_hour');
            $am_or_pm=$request->post('end_AM_PM');
            $appointment_end_hour = $this->transfer_am_pm_format_to_military_format($appointment_end_hour,$am_or_pm);
            $appointment_end_minute = $request->post('appointment_end_minutes');
            return mktime($appointment_end_hour, $appointment_end_minute, 0, $appointment_date_month, $appointment_date_day, $appointment_date_year);
        }
    }

    public function transfer_am_pm_format_to_military_format($appointment_hour,$am_or_pm){

        if ($am_or_pm == 'AM'){
            while ($appointment_hour<12){
                return $appointment_hour;
            }
            return '00';//midnight in military style
        }elseif ($am_or_pm == 'PM')
            $appointment_hour += 12;
            while ($appointment_hour<24){

                return $appointment_hour;
            }
            return '12'; //noon in military style

        }

}



