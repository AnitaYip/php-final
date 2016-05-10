<?php
/**
 * @author: ayip
 */

namespace application;

/**
 * Class UserController - Controller responsible for handling the user specific operations.
 *
 * @package application
 * @subpackage Controller
 */
class UserController
{
    /**
     * @var User model instance
     */
    private $user;

    /**
     * @var UserRepository data access object instance
     */
    private $userRepository;

    /**
     * Instantiates the UserRepository object and injects it into the User model class.
     *
     * UserController constructor.
     */
    public function __construct()
    {
        /*
         * Using the controller itself as a DI Container
         */
        $this->userRepository = new UserRepository();
        $this->user = User::initialize($this->userRepository);
    }

    /**
     * Routes the GET requests to corresponding functions.
     */
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

    /**
     * Routes the POST requests to corresponding functions.
     */
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

    /**
     * Function that fetches the details of the given user and then routes to view user details page on UI.
     *
     * @param int $id Id of the user whose info is to be displayed.
     */
    public function show($id)
    {
        $user = User::find($id);
        include '../view/show.php';
    }

    /**
     * Function that fetches all the users and then routes to the home page on UI.
     */
    public function index()
    {
        $users = User::findAll();
        include '../view/index.php';
    }

    /**
     * Function that routes to edit user details page on UI along with the user info.
     *
     * @param int $id Id of the user whose data is to be updated.
     */
    public function edit($id)
    {
        $user = User::find($id);
        include '../view/user.php';
    }

    /**
     * Function that routes to create new user page on UI.
     */
    public function new()
    {
        $user = User::initialize($this->userRepository);
        include '../view/user.php';
    }

    /**
     * Function that handles save operation when either a new user is
     * created or an existing user data is updated.
     *
     * @param int $id - Null will be passed for new user or
     * else id of the user whose data is being updated.
     */
    public function save($id)
    {
        $user = User::initialize($this->userRepository);
        foreach ($_POST as $key => $value) {
            $user->$key = trim($value);
        }
        $user->id = $id;
        $user->save();
        if (count($user->errors) > 0) {
            echo "Invalid user info:\n";
            foreach ($user->errors as $error) {
                echo $error."\n";
            }
            include '../view/user.php';
        } else {
            include '../view/show.php';
        }
        
    }

    /**
     * Function that handles user delete request.
     *
     * @param int $id Id of the user to be deleted
     */
    public function destroy($id)
    {
        $user = new User($id, null, null);
        if ($user->destroy()) {
            $users = User::findAll();
            include '../view/index.php';
        } else {
            return;
        }
    }
}
