<?php

namespace Model\Entity;

class Employee
{

    private $id;
    private $name;
    private $email;
    private $password;
    private $role;



    public function getId()
    {
        return   $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getName()
    {
        return   $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    public function getEmail()
    {
        return   $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return   $this->password;
    }


    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }




    public function getRole()
    {
        return   $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }



}