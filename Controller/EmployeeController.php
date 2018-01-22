<?php

namespace Controller;

use Library\Boardroom;
use Library\Controller;
use Library\Password;
use Library\Request;
use Library\Session;


class EmployeeController extends Controller
{
    public function showAction(Request $request)
    {
        if(Session::get('admin')){
            Boardroom::setBoardoomId($request);
            $boardroom_id=Boardroom::$boardroom_id;
            $repository = $this->get('repository')->getRepository('Appointments');
            $all_boardrooms=$repository->findAllBoardroomId();
            $repository = $this->get('repository')->getRepository('User');
            $all_employees = $repository->findAllEmployees();

            $data = [
                'all_boardrooms' => $all_boardrooms,
                'all_employees' => $all_employees,
            ];

            if(Session::get('user')){
                return $this->render('index.phtml',$data);
            }
            return $this->direct_render('..\Default\login.phtml');
        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }

    }


    public function deleteAction(Request $request)
    {
        if(Session::get('admin')){

            $employee_id = $request->post('employee_id');
            $repository = $this->get('repository')->getRepository('User');
            $user=$repository->findUserById($employee_id);
            $repository->DeleteUserbyId($employee_id);

            Session::setFlash('Employee ' . $user->getName(). ' has been removed from the list');
            return  $this->get('router')->redirect('/employees');

        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }
    }

    public function addAction(Request $request)
    {
        if(Session::get('admin')){

            Boardroom::setBoardoomId($request);
            $boardroom_id=Boardroom::$boardroom_id;
            $repository = $this->get('repository')->getRepository('Appointments');
            $all_boardrooms=$repository->findAllBoardroomId();
            $repository = $this->get('repository')->getRepository('User');
            $all_employees = $repository->findAllEmployees();
            $data = [
                'all_boardrooms' => $all_boardrooms,
                'all_employees' => $all_employees,
            ];

            return $this->render('add_employee.phtml',$data);
        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }


    }

    public function editAction(Request $request)
    {
        if(Session::get('admin')){
            Boardroom::setBoardoomId($request);
            $employee_id = $request->post('employee_id');

            $repository = $this->get('repository')->getRepository('Appointments');
            $all_boardrooms=$repository->findAllBoardroomId();
            $repository = $this->get('repository')->getRepository('User');
            $all_employees = $repository->findAllEmployees();
            $user=$repository->findUserById($employee_id);
            $data = [
                'all_boardrooms' => $all_boardrooms,
                'all_employees' => $all_employees,
                'user' => $user,
            ];

            return $this->render('edit_employee.phtml',$data);
        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }

    }

    public function saveAction(Request $request)
    {

        if(Session::get('admin')){
            $employee_id = $request->post('employee_id');
            $user_name=$request->post('name');
            $email=$request->post('email');
            $password=$request->post('password');
            $password_repeated=$request->post('password_repeated');

            if($password === $password_repeated){
                $hashed_password = new Password($password);
            }else{
                Session::setFlash('Passwords didn\'t match! Try again!');
                return $this->get('router')->redirect('/employee/add');
            }

            if($request->post('role')== 'admin'){
                $role=$request->post('role');
            }else{
                $role=NULL;
            }

            $repository = $this->get('repository')->getRepository('User');
            $repository->addUser($user_name,$email,$hashed_password,$role);
            if($role){
                Session::setFlash("New {$role} {$user_name} has been successfully added to Employees List");
            }else{
                Session::setFlash("New employee {$user_name} has been successfully added to Employees List");
            }
            return  $this->get('router')->redirect('/employees');
        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }

    }



    public function saveEditedAction(Request $request)
    {

        if(Session::get('admin')){
            $employee_id = $request->post('employee_id');
            $user_name=$request->post('name');
            $email=$request->post('email');
            $password=$request->post('password');
            $password_repeated=$request->post('password_repeated');

            if($password === $password_repeated){
                $hashed_password = new Password($password);
            }else{
                Session::setFlash('Passwords didn\'t match! Try again!');
                return $this->get('router')->redirect('/employees');
            }

            if($request->post('role')== 'admin'){
                $role=$request->post('role');
            }else{
                $role=NULL;
            }

            $repository = $this->get('repository')->getRepository('User');
            $repository->editUserById($employee_id,$user_name,$email,$hashed_password,$role);
            Session::setFlash("The {$user_name}'s info has been successfully updated in the Employees List");
            return  $this->get('router')->redirect('/employees');
        }else{
            Session::setFlash('You don\'t have access to Employees List');
            return $this->get('router')->redirect('/');
        }

    }



}