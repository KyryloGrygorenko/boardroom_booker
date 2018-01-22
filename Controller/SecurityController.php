<?php

namespace Controller;

use Library\Boardroom;
use Library\Controller;
use Library\Request;
use Library\Password;
use Library\Session;
use Model\Form\LoginForm;

class SecurityController extends Controller
{

    public function loginAction(Request $request)   {


        Boardroom::setBoardoomId($request);
        $boardroom_id=Boardroom::$boardroom_id;
        $repository = $this->get('repository')->getRepository('Appointments');
        $all_boardrooms=$repository->findAllBoardroomId();

        $form = new LoginForm($request);

        if ($request->isPost()) {

            if ($form->isValid()) {
                $repo = $this->get('repository')->getRepository('User');
                $password = new Password($form->password);

                $user = $repo->find($form->email,$password);

                $user_id = $repo->findUserId($form->email, $password);

                if (!$user) {
//                    throw new \Exception("Email {$form->email} is not found or password is incorrect ");
                    Session::setFlash("Email {$form->email} is not found or password is incorrect ");
                    return $this->direct_render('login.phtml');
                }

                if ($user){

                    if ($user->getIsAdmin()==true) { // if Admin

                        Session::set('user', $user->getEmail());
                        Session::set('user_id', $user_id);
                        Session::set('admin', $user->getIsAdmin());
                        Session::setFlash('Logged in as admin');

//                        $this->get('router')->redirect('/admin');
                        $this->get('router')->redirect('/');

                    }elseif ($user){// if User exists
                        Session::set('user', $user->getEmail());
                        Session::setFlash('Logged in as user');
                        $this->get('router')->redirect('/');

                    }


                }

                
                Session::setFlash('User not found');
                $this->get('router')->redirect('/login');
            }
            
            Session::setFlash('Fill the fields');
        }
        
        return $this->direct_render('login.phtml',$all_boardrooms);
    }
    
    public function logoutAction()
    {
        Session::remove('user');
        Session::remove('user_id');
        Session::remove('admin');

        $this->get('router')->redirect('/');
    }
    
    public function registerAction($request)
    {

        $form = new LoginForm($request);

        if ($request->isPost()) {

            if ($form->isValid()) {
                $repo = $this->get('repository')->getRepository('User');
                $password = new Password($form->password);

                $user = $repo->find($form->email, $password);
                if ($user){
                    if ($user->getIsAdmin()==true) { // if Admin

                        Session::set('user', $user->getEmail());
                        Session::set('admin', $user->getIsAdmin());
                        Session::setFlash('Logged in');

                        $this->get('router')->redirect('/admin');

                    }elseif ($user){// if User exists
                        Session::set('user', $user->getEmail());
                        Session::setFlash('Logged in');
                        $this->get('router')->redirect('/');

                    }


                }


                Session::setFlash('User not found');
                $this->get('router')->redirect('/login');
            }

            Session::setFlash('Fill the fields');
        }

        return $this->render('register.phtml');
    }
}