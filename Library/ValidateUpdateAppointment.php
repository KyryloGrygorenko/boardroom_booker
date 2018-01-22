<?php
namespace Library;


class ValidateUpdateAppointment
{
    public function ValidateNewAppointment($all_appointments,
                                           $appointment_start_time_unix,
                                           $appointment_end_time_unix,
                                           $notes,
                                           $events_reccure_times,
                                           $is_event_reccuring,
                                           $time_increment_parameter = null,
                                           $boardroom_id)
    {
        $string_start_time=strftime ('%r', $appointment_start_time_unix);
        $string_end_time=strftime ('%r', $appointment_end_time_unix);

        if ( $appointment_start_time_unix == null || $appointment_end_time_unix == null) {

            $error_message = 'Please chose the date for the appointment';
            Session::setFlash($error_message);
            return false;
        }elseif ( $appointment_end_time_unix < $appointment_start_time_unix) {
            $error_message = 'Appointment\'s end time can\'t be earlier than start time';
            Session::setFlash($error_message);
            return false;
        }elseif ( $appointment_start_time_unix < time()) {
            $error_message = 'Appointment can\'t be scheduled in past time';
            Session::setFlash($error_message);
            return false;
        }elseif ( $appointment_start_time_unix == $appointment_end_time_unix) {
            $error_message = 'Appointment\'s  duration should be at least 1 minute';
            Session::setFlash($error_message);
            return false;
        }

        for ($count=1;$count<=$events_reccure_times;$count++){

            foreach ($all_appointments as $appointment) {
                $range = range($appointment->getStartTimeUnix(), $appointment->getEndTimeUnix());

                if (in_array($appointment_start_time_unix, $range)) {
                    $error_message = 'Appointment for time frame ' .$string_start_time . ' - ' . $string_end_time. ' is not available!';
                    Session::setFlash($error_message);
                    return false;
                }

                if (in_array($appointment_end_time_unix, $range)){ ;
                    $error_message = 'Appointment for time frame ' .$string_start_time . ' - ' . $string_end_time. ' is not available!';
                    Session::setFlash($error_message);
                    return false;
                }

            }

            if($time_increment_parameter){
                $appointment_start_time_unix=strtotime($time_increment_parameter, $appointment_start_time_unix);
            }

            if($time_increment_parameter) {
                $appointment_end_time_unix = strtotime($time_increment_parameter, $appointment_end_time_unix);
            }


        }

        if ($is_event_reccuring){
            $success_message = 'Boardroom Booker '. $boardroom_id .' <br> The multiple events from ' . $string_start_time . ' to ' . $string_end_time
                . ' has been updated' . '<br>' . 'The text for this event is: ' .$notes;
            Session::setFlash($success_message);
            return true;
        }


        $success_message = 'Boardroom Booker '. $boardroom_id .' <br> The event ' . $string_start_time . ' to ' . $string_end_time
            . ' has been updated' . '<br>' . 'The text for this event is: ' .$notes;
        Session::setFlash($success_message);
        return true;
    }



}