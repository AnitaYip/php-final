<?php

namespace application;

class User
{
    public static $userRepository;
    public $id;
    public $name;
    public $email;
    public $errors = array();

    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public static function find($id)
    {
        $user = self::$userRepository->find($id);

        return new User($user->id, $user->name, $user->email);
    }

    public static function findAll()
    {
        $result = self::$userRepository->findAll();
        $users = [];
        foreach ($result as $user) {
            array_push($users, new User($user['id'], $user['name'], $user['email']));
        }
        return $users;
    }

    public static function initialize(UserInterface $userRepository)
    {
        self::$userRepository = $userRepository;
        return new User(null, null, null);
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!isset($this->id)) {
            return $this->getUserRepository()->create($this);
        } else {
            return $this->getUserRepository()->update($this);
        }
    }

    public function destroy()
    {
        return $this->getUserRepository()->destroy($this->id);
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

    private function getUserRepository()
    {
        return self::$userRepository;
    }
}
