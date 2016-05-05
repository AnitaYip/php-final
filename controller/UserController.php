<?php

namespace application;

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = User::initialize();
    }

    public function handleGet()
    {
        $uri = $_SERVER['REQUEST_URI'];
        switch ($uri) {
            case '/':
                $this->index();
                break;
            case '/new':
                $this->new();
                break;
            case preg_match('/\d+\/edit/', $uri) ? $uri : !$uri:
                preg_match('/\d+/', $uri, $matches);
                $this->edit($matches[0]);
                break;
            case preg_match('/\d+/', $uri, $matches) ? $uri : !$uri:
                $this->show($matches[0]);
                break;
        }

    }

    public function handlePost()
    {
        switch ($_GET['method']) {
            case 'delete':
                preg_match('/\d+/', $_SERVER['REQUEST_URI'], $matches);
                $this->destroy($matches[0]);
                break;
            case 'update':
                preg_match('/\d+/', $_SERVER['REQUEST_URI'], $matches);
                $this->save($matches[0]);
                break;
            case 'create':
                $this->save(null);
                break;
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        include '../view/show.php';
    }

    public function index()
    {
        $users = User::findAll();
        include '../view/index.php';
    }

    public function edit($id)
    {
        $user = User::find($id);
        include '../view/user.php';
    }

    public function new()
    {
        $user = User::initialize();
        include '../view/user.php';
    }

    public function save($id)
    {
        $user = User::initialize();
        foreach ($_POST as $key => $value) {
            $user->$key = trim($value);
        }
        $user->id = $id;
        $user->save();
        if(count($user->errors) > 0) {
            echo "Invalid user info:\n";
            foreach ($user->errors as $error) {
                echo $error."\n";
            }
            include '../view/user.php';
        } else {
            include '../view/show.php';    
        }
        
    }

    public function destroy($id)
    {
        $user = new User($id, null, null);
        if($user->destroy()){;
            $users = User::findAll();
            include '../view/index.php';
        } else {
            return;
        }
    }
}