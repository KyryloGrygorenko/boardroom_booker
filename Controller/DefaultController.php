<?php

namespace Controller;

use Library\AllYearsRange;
use Library\AmPmOrMilitaryTimeMode;
use Library\Boardroom;
use Library\Controller;
use Library\FirstDayOfTheWeek;
use Library\Month;
use Library\NextAndPreviousMonth;
use Library\WeeksOfTheMonth;
use Library\Request;
use Library\Session;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;

        $am_pm_or_military_hours_mode=AmPmOrMilitaryTimeMode::getAmPmOrMilitaryTimeMode($request);

        $repository = $this->get('repository')->getRepository('Appointments');
        $all_boardrooms=$repository->findAllBoardroomId();

        //Set the first day in a week mode. Sunday or Monday

        $days_mode=FirstDayOfTheWeek::GetFirstDayMode($request);

//        $am_pm_or_military_hours_mode='military_mode';
        $month = new Month($request);

        $next_and_previous_month=new NextAndPreviousMonth($month->current_month_number,$month->current_year);

        $weeks_of_the_month=WeeksOfTheMonth::GetWeeksOfTheMonth(
                $month->days_in_month_count,
                $days_mode,
                $month->text_first_day_of_the_month,
                $month->unix_first_day_in_the_month,
                $month->current_month_number,
                $month->current_year
        );

        $days_objects_by_week=WeeksOfTheMonth::$days_objects_by_week;

        $all_appointments=$repository->findAllAppointmentsByBoardroomId($boardroom_id,$am_pm_or_military_hours_mode);

        foreach ($days_objects_by_week as $day){
            $all_appointments_objects=$repository->findAllAppointmentsInUnixTimeRange(
                $boardroom_id, $day->current_day_unix, $day->next_day_unix,$am_pm_or_military_hours_mode);

            if ($all_appointments){
                $day->all_appointments_objects=$all_appointments_objects;
            }

        }

        $all_months=['January','February','March','April','May','June','July','August','September','October','November','December'];

        $all_years_in_range=AllYearsRange::GetAllYearsFromTo(START_YEAR,END_YEAR);


        $data = [
            '$all_appointments_objects' => $all_appointments_objects,
            'weeks_of_the_month' => $weeks_of_the_month,
            'days_mode' => $days_mode,
            'all_months' => $all_months,
            'all_years_in_range' => $all_years_in_range,
            'current_month_number' => $month->current_month_number,
            'current_year' => $month->current_year,
            'next_and_previous_month' => $next_and_previous_month,
            'days_objects_by_week' => $days_objects_by_week,
            'boardroom_id' => $boardroom_id,
            'all_boardrooms' => $all_boardrooms,
            'am_pm_or_military_hours_mode' => $am_pm_or_military_hours_mode,

        ];

        if(Session::get('user')){

            return $this->render('index.phtml',$data);
        }
        return $this->direct_render('login.phtml',$data);

    }








}