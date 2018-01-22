<?php

namespace Model;

use Library\PdoAwareTrait;
use Model\Entity\Appointment;
use Model\Entity\Appointments;
use Model\Entity\Article;


class AppointmentsRepository
{

    use PdoAwareTrait;

    public function findAllAppointmentsByBoardroomId($boardroom_id,$am_pm_or_military_hours_mode=null)
    {
        $collection = [];
        $sth = $this->pdo->query("SELECT * FROM appointments WHERE boardroom_id= {$boardroom_id} ORDER BY `start_time` ASC ");

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $appointments = (new Appointment())
                ->setId($res['id'])
                ->setAdminId($res['admin_id'])
                ->setStartTimeUnix($res['start_time'])
                ->setEndTimeUnix($res['end_time'])
                ->setStartTimeString($res['start_time'],$am_pm_or_military_hours_mode)
                ->setEndTimeString($res['end_time'],$am_pm_or_military_hours_mode)
                ->setBoardroomId($res['boardroom_id'])
                ->setEventReccuringType($res['event_reccuring_type'])
                ->setSubmitted($res['submitted'])
                ->setStartAndEndHoursAndMinutes($res['start_time'],$res['end_time']);

            $collection[] = $appointments;
        }

        return $collection;
    }

    public function findAllAppointmentsInUnixTimeRange($boardroom_id, $start_time_unix, $end_time_unix,$am_pm_or_military_hours_mode)
    {
        $collection = [];
        $sth = $this->pdo->query("
        SELECT * FROM appointments WHERE boardroom_id= {$boardroom_id} 
        AND `start_time`>= {$start_time_unix} AND `end_time` <= {$end_time_unix}   
        ORDER BY `start_time` ASC ");

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $appointments = (new Appointment())
                ->setId($res['id'])
                ->setAdminId($res['admin_id'])
                ->setStartTimeUnix($res['start_time'])
                ->setEndTimeUnix($res['end_time'])
                ->setStartTimeString($res['start_time'],$am_pm_or_military_hours_mode)
                ->setEndTimeString($res['end_time'],$am_pm_or_military_hours_mode)
                ->setBoardroomId($res['boardroom_id'])
                ->setEventReccuringType($res['event_reccuring_type'])
                ->setSubmitted($res['submitted'])
                ->setStartAndEndHoursAndMinutes($res['start_time'],$res['end_time']);
            $collection[] = $appointments;
        }

        return $collection;
    }



    public function findAllBoardroomId()
    {
        $collection = [];
        $sth = $this->pdo->query("SELECT `boardroom_id` FROM appointments GROUP BY boardroom_id ");

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $collection[] = $res['boardroom_id'];
        }

        return $collection;
    }

    public function addAppointment($admin_id,$appointment_start_time_unix,$appointment_end_time_unix,$boardroom_id,$notes,$user_id,$event_reccuring_type,$submitted,$connection_id=null)
    {
        $sth = $this->pdo->prepare("INSERT INTO `appointments` (`id`, `admin_id`, `start_time`, `end_time`, `boardroom_id`, `notes`, `user_id`, `connection_id`, `event_reccuring_type`,`submitted` ) 
        VALUES (NULL, :admin_id, :start_time, :end_time, :boardroom_id, :notes, :user_id, :connection_id, :event_reccuring_type, :submitted);");
        $sth->execute([
            'admin_id' => $admin_id,
            'start_time' => $appointment_start_time_unix,
            'end_time' => $appointment_end_time_unix,
            'boardroom_id' => $boardroom_id,
            'notes' => $notes,
            'user_id' => $user_id,
            'connection_id' => $connection_id,
            'event_reccuring_type' => $event_reccuring_type,
            'submitted' => $submitted,

        ]);

    }
    public function findAppointmentById($id,$am_pm_or_military_hours_mode=null)
    {
        $sth = $this->pdo->query("SELECT * FROM `appointments` where `id`={$id} ;");
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        $appointment = (new Appointment())
            ->setId($res['id'])
            ->setAdminId($res['admin_id'])
            ->setUserId($res['user_id'])
            ->setStartTimeUnix($res['start_time'])
            ->setEndTimeUnix($res['end_time'])
            ->setStartTimeString($res['start_time'],$am_pm_or_military_hours_mode)
            ->setEndTimeString($res['end_time'],$am_pm_or_military_hours_mode)
            ->setBoardroomId($res['boardroom_id'])
            ->setNotes($res['notes'])
            ->setConnectionId($res['connection_id'])
            ->setEventReccuringType($res['event_reccuring_type'])
            ->setSubmitted($res['submitted'])
            ->setStartAndEndHoursAndMinutes($res['start_time'],$res['end_time']);
        return $appointment;
    }


    public function findAppointmentIdsByAppointmentId($connection_id)
    {
        $sth = $this->pdo->query("SELECT * FROM `appointments` where `connection_id`={$connection_id} ;");
        $collection=[];
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $collection[] = $res['id'];
        }
        return $collection;
    }



    public function DeleteAppointmentById($appointment_id)
    {

        $sth = $this->pdo->prepare("DELETE FROM `appointments` WHERE `appointments`.`id` =:appointment_id;");
        $sth->execute([
            'appointment_id' => $appointment_id,
        ]);
    }


    public function DeleteMultipleAppointmentByConnectionId($connection_id)
    {

        $sth = $this->pdo->prepare("DELETE FROM `appointments` WHERE `appointments`.`connection_id` =:connection_id;");
        $sth->execute([
            'connection_id' => $connection_id,
        ]);
    }



    public function UpdateAppointmentById($appointment_id,$appointment_start_time_unix,$appointment_end_time_unix,$notes,$user_id)

    {
        $sth = $this->pdo->prepare("UPDATE `appointments` SET `start_time`=:start_time, `end_time`=:end_time,`notes`=:notes, `user_id`=:user_id
        WHERE `id` =:appointment_id");

//        UPDATE `appointments` SET `start_time`=1, `end_time`=2, `notes`='text', `user_id`=3 WHERE `id` =64;

        $sth->execute([
            'start_time' => $appointment_start_time_unix,
            'end_time' => $appointment_end_time_unix,
            'notes' => $notes,
            'user_id' => $user_id,
            'appointment_id' => $appointment_id,
        ]);
    }

    public function UpdateMultipleAppointmentByConnectionId($appointment_id,$appointment_start_time_unix,$appointment_end_time_unix,$notes,$user_id,$connection_id)

    {
        $sth = $this->pdo->prepare("UPDATE `appointments` SET `start_time`=:start_time, `end_time`=:end_time,`notes`=:notes, `user_id`=:user_id
        WHERE `connection_id` =:connection_id");

        $sth->execute([
            'start_time' => $appointment_start_time_unix,
            'end_time' => $appointment_end_time_unix,
            'notes' => $notes,
            'user_id' => $user_id,
            'connection_id' => $connection_id,
        ]);
    }


    
    
}
