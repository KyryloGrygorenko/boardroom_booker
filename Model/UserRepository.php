<?php

namespace Model;

use Library\PdoAwareTrait;
use Model\Entity\Employee;
use Model\Entity\User;

class UserRepository
{
    use PdoAwareTrait;

    public function find($email, $password)
    {
        $sth = $this->pdo->prepare('SELECT * FROM users WHERE email = :email AND password = :password');

        $sth->execute([
            'email' => $email,
            'password' => $password,
        ]);


        $res = $sth->fetch(\PDO::FETCH_ASSOC);

        $isAdmin=false;

        if ($res['role']=='admin'){
            $isAdmin=true;
        }
        
        if (!$res) {
            return null;
        }

        return (new User())->setEmail($email)->setIsAdmin($isAdmin);
    }

    public function findUserId($email, $password)
    {
        $sth = $this->pdo->prepare('SELECT id FROM users WHERE email = :email AND password = :password');

        $sth->execute([
            'email' => $email,
            'password' => $password
        ]);


        $res = $sth->fetch(\PDO::FETCH_ASSOC);


        return ($res['id']);
    }



    public function create()
    {

    }

    public function findAllEmployees()
    {

        $sth = $this->pdo->query('SELECT * FROM `users` WHERE `role` IS NULL');

        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)) {

            $employees = (new Employee())
                ->setId($res['id'])
                ->setName($res['name'])
                ->setEmail($res['email'])
//                ->setPassword($res['password'])
                ->setRole($res['role']);

            $collection[] = $employees;
        }

        return $collection;
    }

    public function findUserById($user_id)
    {
        $sth = $this->pdo->query("SELECT * FROM `users` where `id`={$user_id} ;");
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        $user = (new User())
            ->setId($res['id'])
            ->setName($res['name'])
            ->setEmail($res['email'])
//            ->password($res['password'])
            ->setRole($res['role']);


        return $user;
    }


    public function DeleteUserById($user_id)
    {
        $sth = $this->pdo->prepare("DELETE FROM `users` WHERE `users`.`id` =:user_id;");
        $sth->execute([
            'user_id' => $user_id,
        ]);
    }

    public function addUser($user_name,$email,$password,$role)
    {
        $sth = $this->pdo->prepare("INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `activation`) 
        VALUES (NULL, :name, :email, :password, :role, NULL);");
        $sth->execute([
            'name' => $user_name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);
    }




    public function editUserById($user_id,$user_name,$email,$password,$role)

    {
        $sth = $this->pdo->prepare("UPDATE `users` SET `name`=:name, `email`=:email,`password`=:password, `role`=:role
        WHERE `id` =:id");

        $sth->execute([
            'id' => $user_id,
            'name' => $user_name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ]);
    }


}