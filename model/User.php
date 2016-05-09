<?php

namespace application;

use mysqli;

class User
{
    private static $connection = null;
    public $id;
    public $name;
    public $email;
    public $errors = array();

    public static function getConnection()
    {
        $connection = self::$connection;
        if (!isset($connection)) {
            $connection = new mysqli('192.168.10.10:3306', 'homestead', 'secret', 'homestead');
        }
        return $connection;
    }

    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public static function find($id)
    {
        $connection = self::getConnection();
        $result = $connection->query("SELECT * FROM users WHERE id = $id");

        if (!$result) {
            echo 'query error';
            return false;
        }

        if ($result->num_rows === 0) {
            echo "User not found with id: $id";
            return false;
        }
        $user = $result->fetch_object();
        return new User($user->id, $user->name, $user->email);
    }

    public static function findAll()
    {
        $connection = self::getConnection();
        $result = $connection->query("select * from users");

        if (!$result) {
            echo 'query error';
            exit;
        }
        if ($result->num_rows == 0) {
            echo "No users found in db";
            exit;
        }
        $users = [];
        foreach ($result as $user) {
            array_push($users, new User($user['id'], $user['name'], $user['email']));
        }
        return $users;
    }

    public static function initialize()
    {
        return new User(null, null, null);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $connection = self::getConnection();
        if (!isset($this->id)) {
            $result = $connection->query("INSERT INTO users (name, email) VALUES ('$this->name','$this->email')");
            $this->id = $connection->insert_id;
            if ($result) {
                return $this;
            } else {
                echo "User creation failed.\n";
                return false;
            }
        } else {
            $query = "UPDATE users SET name='$this->name', email='$this->email' WHERE id=$this->id";
            $result = $connection->query($query);
            if (isset($result)) {
                echo "User updated successfully.\n";
                return true;
            } else {
                echo "User updation failed.\n";
                return false;
            }
        }
    }

    public function destroy()
    {
        $result = self::getConnection()->query("DELETE FROM users WHERE id = $this->id");
        if (isset($result)) {
            echo "User deleted successfully.\n";
            return true;
        } else {
            echo "User deletion failed.\n";
            return false;
        }
    }

    public function validate()
    {
        $this->errors = array();
        if ($this->name && $this->email) {
            return true;
        } else {
            if (!$this->name) {
                array_push($this->errors, "Name is a mandatory field.\n");
            }
            if (!$this->email) {
                array_push($this->errors, "Email is a mandatory field.\n");
            }
        }
        return false;
    }

}
