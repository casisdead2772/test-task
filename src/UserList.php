<?php

namespace App;

use mysqli;

/**
 * Class UserList
 * @package App
 */
class UserList
{
    public array $userIdList;

    public function __construct($searchQuery)
    {
        $userArray = [];

        if(!class_exists(\App\User::class)){
            exit('class User not exists');
        }

        $conn = new mysqli('localhost', 'root', '', 'test');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        $sql = "SELECT id FROM users WHERE MATCH (name, lastname, city) AGAINST ('$searchQuery')";
        $result = $conn->query($sql);
        while ($row  = $result->fetch_assoc()) {
            $userArray[] = $row['id'];
        }

        $this->userIdList = $userArray;
    }

    public function getArrayUsersInstances()
    {
        $userInstanceArray = [];

        foreach($this->userIdList as $userId){
            $userInstanceArray[] = User::getUserById($userId);
        }

        return $userInstanceArray;
    }

    public function deleteUsers()
    {
        foreach($this->userIdList as $userId){
            User::deleteUserById($userId);
        }
    }




}
