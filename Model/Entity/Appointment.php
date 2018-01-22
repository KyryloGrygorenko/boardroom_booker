<?php

namespace Model\Entity;

class Appointment
{

    private $id;
    private $admin_id;
    private $start_time_unix;
    private $end_time_unix;
    private $start_time_string;
    private $end_time_string;
    private $boardroom_id;
    private $notes;
    private $user_id;
    private $connection_id;
    private $event_reccuring_type;
    private $submitted;

    private $start_time_hours;
    private $start_time_minutes;
    private $end_time_hours;
    private $end_time_minutes;

    public $start_hours_am_pm_mode;
    public $end_hours_am_pm_mode;




    public function getId()
    {
        return   $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId()
    {
        return   $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }




    public function getAdminId()
    {
        return   $this->admin_id;
    }

    public function setAdminId($admin_id)
    {
        $this->admin_id = $admin_id;
        return $this;
    }


    public function getStartTimeUnix()
    {
        return   $this->start_time_unix;
    }

    public function setStartTimeUnix($start_time_unix)
    {
        $this->start_time_unix = $start_time_unix;
        return $this;
    }

    public function getEndTimeUnix()
    {
        return   $this->end_time_unix;
    }


    public function setEndTimeUnix($end_time_unix)
    {
        $this->end_time_unix = $end_time_unix;
        return $this;
    }




    public function getStartTimeString()
    {
        return   $this->start_time_string;
    }

    public function setStartTimeString($start_time_string,$am_pm_or_military_hours_mode)
    {
        if($am_pm_or_military_hours_mode=='am_pm_mode'){
            $date=date("h:i", $start_time_string);
            $date_hours=date("H", $start_time_string);
            if ($date_hours > 11){
                $date .= 'pm';
            }else{
                $date .= 'am';
            }
            $this->start_time_string = $date;
            return $this;
        }elseif ($am_pm_or_military_hours_mode=='military_mode')

            $this->start_time_string = date("H:i", $start_time_string);
        return $this;


    }




    public function getEndTimeString()
    {
        return   $this->end_time_string;
    }

    public function setEndTimeString($end_time_string,$am_pm_or_military_hours_mode)
    {
        if($am_pm_or_military_hours_mode=='am_pm_mode'){
            $date=date("h:i", $end_time_string);
            $date_hours=date("H", $end_time_string);
            if ($date_hours > 11){
                $date .= 'pm';
            }else{
                $date .= 'am';
            }
            $this->end_time_string = $date;
            return $this;
        }elseif ($am_pm_or_military_hours_mode=='military_mode')

            $this->end_time_string = date("H:i", $end_time_string);
        return $this;
    }


    public function getBoardroomId()
    {
        return   $this->boardroom_id;
    }

    public function setBoardroomId($boardroom_id)
    {
        $this->boardroom_id = $boardroom_id;
        return $this;
    }

    public function getNotes()
    {
        return   $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getConnectionId()
    {
        return   $this->connection_id;
    }

    public function setConnectionId($connection_id)
    {
        $this->connection_id = $connection_id;
        return $this;
    }


    public function getEventReccuringType()
    {
        return   $this->event_reccuring_type;
    }

    public function setEventReccuringType($event_reccuring_type)
    {
        $this->event_reccuring_type = $event_reccuring_type;
        return $this;
    }


    public function getSubmitted()
    {
        return   $this->submitted;
    }

    public function setSubmitted($submitted)
    {
        $this->submitted = $submitted;
        return $this;
    }

    public function getStartTimeHours()
    {
        return   $this->start_time_hours;
    }

    public function setStartTimeHours($start_time_unix)
    {
        $this->start_time_hours = (date("H",$start_time_unix));
        return $this;
    }


    public function getStartTimeMinutes()
    {
        return   $this->start_time_minutes;
    }

    public function setStartTimeMinutes($start_time_unix)
    {
        $this->start_time_minutes = (date("i",$start_time_unix));
        return $this;
    }


    public function getEndTimeHours()
    {
        return   $this->end_time_hours;
    }

    public function setEndTimeHours($start_time_unix)
    {
        $this->end_time_hours = (date("H",$start_time_unix));
        return $this;
    }


    public function getEndTimeMinutes()
    {
        return   $this->end_time_minutes;
    }

    public function setEndTimeMinutes($start_time_unix)
    {
        $this->end_time_minutes = (date("i",$start_time_unix));
        return $this;
    }


    public function setStartAndEndHoursAndMinutes($start_time_unix,$end_time_unix)
    {

        $this->setStartTimeHours($start_time_unix);
        $this->setStartHoursAmPmMode($this->start_time_hours);
        $this->setStartTimeMinutes($start_time_unix);
        $this->setEndTimeHours($end_time_unix);
        $this->setEndHoursAmPmMode($this->end_time_hours);
        $this->setEndTimeMinutes($end_time_unix);
        return $this;

    }


    public function setStartHoursAmPmMode($hours)
    {
        if ($hours > 11) {
            $this->start_hours_am_pm_mode = 'pm';
            if($hours>12){
                $this->start_time_hours = $hours-12;
            }
        }else {
            $this->start_hours_am_pm_mode = 'am';
        }
        return $this;

    }

    public function setEndHoursAmPmMode($hours)
    {
        if ($hours > 11) {
            $this->end_hours_am_pm_mode = 'pm';
            if($hours>12){
                $this->end_time_hours = $hours-12;
            }
        }else {
            $this->end_hours_am_pm_mode = 'am';
        }
        return $this;
    }


}