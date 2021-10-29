<?php


namespace App;

use mysqli;
use stdClass;

/**
 * Class User
 * @package App
 */
class User
{
    public ?int $id;
    public string $name;
    public string $lastname;
    public string $date;
    public string $city;
    public int $sex = 0;
    protected mysqli $conn;

    public function __construct($name, $lastname, $date, $city, $sex, $id = null)
    {
        $this->name = $name;
        $this->lastname = $lastname;
        $this->date = $date;
        $this->sex = $sex;
        $this->city = $city;

        if(isset($id)){
            $this->id = $id;
            echo "User selected successfully<br/>";
        } else {
            $this->conn = new mysqli('localhost', 'root', '', 'test');
            if ($this->conn->connect_error) {
                die('Connection failed: ' . $this->conn->connect_error);
            }

            $sql = "INSERT INTO users (name, lastname, date, sex, city)
        VALUES ('$name', '$lastname', '$date', '$sex', '$city')";

            if (($sex === 0 || $sex === 1) && $this->conn->query($sql) === TRUE) {
                echo 'New record created successfully<br/>';
            } else {
                echo 'Error: ' . $sql . '<br>' . $this->conn->error;
            }
            $this->id = mysqli_insert_id($this->conn);
        }

    }

    public function createUserTable()
    {
        $sql = "CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                lastname VARCHAR(30) NOT NULL,
                date DATETIME NOT NULL,
                sex INT NOT NULL, 
                city VARCHAR(50)
            )";

        if ($this->conn->query($sql) === TRUE) {
            echo 'Table users created successfully';
        } else {
            echo 'Error creating table: ' . $this->conn->error;
        }
    }

    public static function deleteUserById($id): void
    {
        $conn = new mysqli('localhost', 'root', '', 'test');
        $sql = "DELETE FROM users WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Record with user id =  {$id} deleted successfully<br/>";
        } else {
            echo 'Error: ' . $sql . '<br>' . $conn->error;
        }
    }

    public static function dateToAge($userId)
    {
        $conn = new mysqli('localhost', 'root', '', 'test');
        $sql = "SELECT date FROM users WHERE id = $userId LIMIT 1";
        $userDate = $conn->query($sql);

        if (mysqli_num_rows($userDate)) {
            $row = $userDate->fetch_assoc();
            $date = $row['date'];
            return date_diff(date_create($date), date_create('now'))->y;
        }

        return "User with this id not founded";
    }

    public static function intToStringSex($userId): string
    {
        $conn = new mysqli('localhost', 'root', '', 'test');
        $sql = "SELECT sex FROM users WHERE id = $userId LIMIT 1";
        $userSex = $conn->query($sql);

        if (mysqli_num_rows($userSex)) {
            $row = $userSex->fetch_assoc();
            $sex = $row['sex'];
            switch($sex){
                case 0:
                    return 'Male';
                case 1:
                    return 'Female';
            }
        }
        return "User with this id not founded";
    }

    public static function getUserById($id)
    {
        $conn = new mysqli('localhost', 'root', '', 'test');
        $sql = "SELECT * FROM users WHERE id = $id LIMIT 1";
        $userSex = $conn->query($sql);
        if (mysqli_num_rows($userSex)) {
            $row = $userSex->fetch_assoc();
            return new User(
                $row['name'],
                $row['lastname'],
                $row['date'],
                $row['city'],
                $row['sex'],
                $row['id']);
        }
        echo 'User not found<br/>';
    }

    public function getFormatUser(bool $age = false, bool $sex = false): stdClass
    {
        $formatUser = new stdClass();
        $formatUser->id = $this->id;
        $formatUser->name = $this->name;
        $formatUser->lastname = $this->lastname;
        $formatUser->date = $age ? self::dateToAge($this->id) : $this->date;
        $formatUser->city = $this->city;
        $formatUser->sex = $sex ? self::intToStringSex($this->id): $this->sex;
        return $formatUser;
    }
}
