<?php
namespace Library;

abstract class FilterAppointments
{
    public static $boardroom_id;


    public static function ExcludeCurrentAppoitmentFromAllAppointments($all_appointments,$appointment_id){

        foreach ($all_appointments as $appointment){
            if($appointment->getId() != $appointment_id) {
                $all_filtered_appointments[] = $appointment;
            }
        }
       return $all_filtered_appointments;

    }


    public static function ExcludeCurrentMultipleAppoitmentsFromAllAppointments($all_appointments,$connected_appointmnets_ids_array){

        foreach ($all_appointments as $appointment){
            if(
                $appointment->getId() != $connected_appointmnets_ids_array[0] &&
                $appointment->getId() != $connected_appointmnets_ids_array[1] &&
                $appointment->getId() != $connected_appointmnets_ids_array[2] &&
                $appointment->getId() != $connected_appointmnets_ids_array[3]

            ){
                $all_filtered_appointments[]=$appointment;
            };
        }

       return $all_filtered_appointments;

    }

}