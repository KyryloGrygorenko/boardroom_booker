<?php

namespace Controller;

use Library\Boardroom;
use Library\Controller;
use Model\Form\FeedbackForm;
use Model\FeedbackRepository;
use Model\Entity\Feedback;
use Library\Request;
use Library\Session;

class ExceptionController extends Controller
{
    public function handleAction(Request $request, \Exception $exception)
    {

        $repository = $this->get('repository')->getRepository('Appointments');
        $all_boardrooms=$repository->findAllBoardroomId();

        $data = [
            'exception' => $exception,
            'all_boardrooms' => $all_boardrooms,

        ];


        return $this->render('handle.phtml', $data);
    }
}