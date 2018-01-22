<?php

namespace Controller;

use Library\AmPmOrMilitaryTimeMode;
use Library\AppointmentUnix;
use Library\AppointmentUnixByParameter;
use Library\Boardroom;
use Library\Controller;
use Library\FilterAppointments;
use Library\GetHoursAndMinutesArray;
use Library\TimeIncrementParameter;
use Library\ValidateNewAppointment;
use Library\ValidateUpdateAppointment;
use Library\Request;
use Library\Session;


class AppointmentController extends Controller
{
    public function showAction(Request $request)
    {
        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;

        $am_pm_or_military_hours_mode=AmPmOrMilitaryTimeMode::getAmPmOrMilitaryTimeMode($request);

        $repository = $this->get('repository')->getRepository('Appointments');
        $appointment_id=$request->get('id');
        $appointment=$repository->findAppointmentById($appointment_id,$am_pm_or_military_hours_mode);
        $user_id=$appointment->getUserId();
        $all_boardrooms=$repository->findAllBoardroomId();
        $repository = $this->get('repository')->getRepository('User');
        $user=$repository->findUserById($user_id);
        $user_name=$user->getName();

        if($appointment->getConnectionId()){
            $appointment_is_multiple=true;
        }else{
            $appointment_is_multiple='';
        }
        $repository = $this->get('repository')->getRepository('User');
        $all_employees = $repository->findAllEmployees();
        $hours=GetHoursAndMinutesArray::getHoursArray();
        $minutes=GetHoursAndMinutesArray::getMinutesArray();


        $data = [
            'all_boardrooms' => $all_boardrooms,
            'appointment' => $appointment,
            'user_name' => $user_name,
            'appointment_is_multiple' => $appointment_is_multiple,
        ];


        if(Session::get('user')){
            return $this->render('Appointment.phtml',$data);
        }
        return $this->render('login.phtml',$data);



    }

    public function createAction(Request $request)
    {
        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;
        $repository = $this->get('repository')->getRepository('Appointments');
        $all_boardrooms=$repository->findAllBoardroomId();
        $repository = $this->get('repository')->getRepository('User');
        $all_employees = $repository->findAllEmployees();
        $hours=GetHoursAndMinutesArray::getHoursArray();
        $minutes=GetHoursAndMinutesArray::getMinutesArray();


        $data = [
            'all_boardrooms' => $all_boardrooms,
            'all_employees' => $all_employees,
            'hours' => $hours,
            'minutes' => $minutes,
        ];


        if(Session::get('user')){
            return $this->render('BookAppointment.phtml',$data);
        }
        return $this->render('login.phtml',$data);


    }


    public function updateAction(Request $request)
    {
        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;
        $repository = $this->get('repository')->getRepository('Appointments');
        $all_boardrooms=$repository->findAllBoardroomId();

        $hours=GetHoursAndMinutesArray::getHoursArray();
        $minutes=GetHoursAndMinutesArray::getMinutesArray();

        $am_pm_or_military_hours_mode=AmPmOrMilitaryTimeMode::getAmPmOrMilitaryTimeMode($request);

        $repository = $this->get('repository')->getRepository('Appointments');
        $appointment_id=$request->post('appointment_id');
        $appointment=$repository->findAppointmentById($appointment_id,$am_pm_or_military_hours_mode);

        $repository = $this->get('repository')->getRepository('User');
        $all_employees = $repository->findAllEmployees();

        $user_id=$appointment->getUserId();
        $user=$repository->findUserById($user_id);
        $user_name=$user->getName();

        if($appointment->getConnectionId()){
            $appointment_is_multiple=true;
        }else{
            $appointment_is_multiple='';
        }


        $data = [
            'all_boardrooms' => $all_boardrooms,
            'all_employees' => $all_employees,
            'appointment' => $appointment,
            'hours' => $hours,
            'minutes' => $minutes,
            'user_name' => $user_name,
            'appointment_is_multiple' => $appointment_is_multiple,
        ];




        if(Session::get('user')){
            return $this->render('AppointmentUpdate.phtml',$data);
        }
        return $this->render('login.phtml',$data);

    }



    public function deleteAction(Request $request)
    {

        $appointment_id = $request->post('appointment_id');
        $apply_to_all_connected_appointments = $request->post('apply_to_all_connected_appointments');

        $am_pm_or_military_hours_mode=AmPmOrMilitaryTimeMode::getAmPmOrMilitaryTimeMode($request);

        $repository = $this->get('repository')->getRepository('Appointments');
        $appointment=$repository->findAppointmentById($appointment_id,$am_pm_or_military_hours_mode);


        if($appointment->getConnectionId() && $apply_to_all_connected_appointments){ //check if the appointment is single or it is a part of the group
            $repository->DeleteMultipleAppointmentByConnectionId($appointment->getConnectionId());
            Session::setFlash('The multiple events ' .$appointment->getStartTimeString() . '-' . $appointment->getEndTimeString() . ' has been deleted');
        }else{
            $repository->DeleteAppointmentById($appointment_id);
            Session::setFlash('The event ' .$appointment->getStartTimeString() . '-' . $appointment->getEndTimeString() . ' has been deleted');
        }


        return  $this->get('router')->redirect('/');

    }


    public function saveAction(Request $request)
    {

        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;

        $repository = $this->get('repository')->getRepository('Appointments');
        $all_appointments= $repository->findAllAppointmentsByBoardroomId($boardroom_id);

        $new_appointment_unix=new AppointmentUnix($request);

        $appointment_start_time_unix=$new_appointment_unix->appointment_start_time_unix;
        $appointment_end_time_unix=$new_appointment_unix->appointment_end_time_unix;

        $user_id=$request->post('employee_id');
        $admin_id=Session::get('user_id');

        $notes=$request->post('notes');
        $is_event_reccuring=(boolean)$request->post('is_event_reccuring');
        $if_event_reccuring_how_often=$request->post('if_event_reccuring_how_often');
        $events_reccure_times=$request->post('events_reccure_times');
        $event_reccuring_type=$request->post('if_event_reccuring_how_often');

        $time_increment_parameter=TimeIncrementParameter::getTimeIncrementParameter($if_event_reccuring_how_often);
        $submitted=date("Y-m-d H:i:s");

        $validate= new ValidateNewAppointment();

        $is_validation_passed= $validate->ValidateNewAppointment(
            $all_appointments,
            $appointment_start_time_unix,
            $appointment_end_time_unix,
            $notes,$events_reccure_times,
            $is_event_reccuring,
            $time_increment_parameter,
            $boardroom_id
        );

        if (! $is_validation_passed){
            $this->get('router')->redirect('/book_appointment');
        }

        if($is_event_reccuring){
            $time_increment_parameter=TimeIncrementParameter::getTimeIncrementParameter($if_event_reccuring_how_often);
            $connection_id=$appointment_start_time_unix;//secondary key to group appointments by it. Must be a unique value for each group.
        }

        if($admin_id && $appointment_start_time_unix && $appointment_end_time_unix && $boardroom_id && $user_id){

            if($is_event_reccuring){
                for ($count=1;$count<=$events_reccure_times;$count++){
                    $repository->addAppointment($admin_id,$appointment_start_time_unix,$appointment_end_time_unix,$boardroom_id,$notes,$user_id,$event_reccuring_type,$submitted,$connection_id);
                    $appointment_start_time_unix=strtotime($time_increment_parameter, $appointment_start_time_unix);
                    $appointment_end_time_unix=strtotime($time_increment_parameter, $appointment_end_time_unix);
                }
            }else{
                $repository->addAppointment($admin_id,$appointment_start_time_unix,$appointment_end_time_unix,$boardroom_id,$notes,$user_id,$event_reccuring_type,$submitted);
            }

        }

        $this->get('router')->redirect('/');
    }




    public function saveUpdateAction(Request $request)
    {
        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;

        $apply_to_all_connected_appointments = $request->post('apply_to_all_connected_appointments');

        $appointment_id = $request->post('appointment_id');
        $old_appointment_start_time_unix=$request->post('old_appointment_start_hour');
        $appointment_date_year=date('Y',$old_appointment_start_time_unix);
        $appointment_date_month=date('m',$old_appointment_start_time_unix);
        $appointment_date_day=date('d',$old_appointment_start_time_unix);

        $new_appointment_unix=new AppointmentUnixByParameter();
        $updated_appointment_start_time_unix=$new_appointment_unix->string_start_date_to_unix($request,$appointment_date_year,$appointment_date_month,$appointment_date_day);
        $updated_appointment_end_time_unix=$new_appointment_unix->string_end_date_to_unix($request,$appointment_date_year,$appointment_date_month,$appointment_date_day);
        $notes=$request->post('notes');
        $user_id=$request->post('employee_id');
        $connection_id=$request->post('connection_id');

        $repository = $this->get('repository')->getRepository('Appointments');
        $all_appointments= $repository->findAllAppointmentsByBoardroomId($boardroom_id);

        $appointment_object=$repository->findAppointmentById($appointment_id);
        $if_event_reccuring_how_often=$appointment_object->getEventReccuringType();

        $time_increment_parameter=TimeIncrementParameter::getTimeIncrementParameter($if_event_reccuring_how_often);

        $all_appointments=FilterAppointments::ExcludeCurrentAppoitmentFromAllAppointments($all_appointments,$appointment_id);

        if($connection_id){
            $connected_appointmnets_ids_array=$repository->findAppointmentIdsByAppointmentId($connection_id);
            $events_reccure_times=count($connected_appointmnets_ids_array);
            $all_appointments= FilterAppointments::ExcludeCurrentMultipleAppoitmentsFromAllAppointments($all_appointments,$connected_appointmnets_ids_array);

        }else{
            $events_reccure_times=1;
        }

        $is_event_reccuring=$apply_to_all_connected_appointments;

        $validate= new ValidateUpdateAppointment();
        $is_validation_passed= $validate->ValidateNewAppointment(
            $all_appointments,
            $updated_appointment_start_time_unix,
            $updated_appointment_end_time_unix,
            $notes,$events_reccure_times,
            $is_event_reccuring,
            $time_increment_parameter,
            $boardroom_id
        );



        if (! $is_validation_passed){
            $this->get('router')->redirect('/appointment?id=' .$appointment_id);
        }


        if($connected_appointmnets_ids_array && $apply_to_all_connected_appointments){
                foreach ($connected_appointmnets_ids_array as $appointment_id){
                $repository->UpdateAppointmentById($appointment_id, $updated_appointment_start_time_unix, $updated_appointment_end_time_unix, $notes, $user_id);
                $updated_appointment_start_time_unix = strtotime($time_increment_parameter, $updated_appointment_start_time_unix);
                $updated_appointment_end_time_unix = strtotime($time_increment_parameter, $updated_appointment_end_time_unix);
            }
        }else{
            $repository->UpdateAppointmentById($appointment_id,$updated_appointment_start_time_unix,$updated_appointment_end_time_unix,$notes,$user_id);
        }

        $this->get('router')->redirect('/');

    }
}