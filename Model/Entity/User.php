<?php

namespace Model\Entity;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $isAdmin= false;
    private $admin_id;
    private $role;

    
    /**
     * @return mixed
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }



    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }




    public function getEmail()
    {
        return $this->email;
    }


    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }


    public function getName()
    {
        return $this->name;
    }


    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }




    public function getRole()
    {
        return $this->role;
    }


    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }




    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
        
        return $password;
    }
}