<?php
/**
 * @author: ayip
 */

namespace application;

/**
 * Class User - Model class responsible for communicating with the Repository class.
 *
 * @package application
 * @subpackage Model
 */
class User
{
    /**
     * @var UserInterface - Instance of the data access object interface which proxies the DB operations.
     */
    public static $userRepository;
    /**
     * @var int Id of the user
     */
    public $id;
    /**
     * @var string name of the user
     */
    public $name;
    /**
     * @var string email of the user
     */
    public $email;
    /**
     * @var string[] array of validation errors
     */
    public $errors = array();

    /**
     * User constructor Creates new instance of the user.
     *
     * @param int $id unique identifier of the user
     * @param string $name user name
     * @param string $email user email
     */
    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Static method to find the user info for the given $id.
     *
     * @param int $id unique identifier of the user
     * @return User object
     */
    public static function find($id)
    {
        $user = self::$userRepository->find($id);

        return new User($user->id, $user->name, $user->email);
    }

    /**
     * Static method to get the info of all the users in the database.
     *
     * @return User[] - array of user objects
     */
    public static function findAll()
    {
        $result = self::$userRepository->findAll();
        $users = [];
        foreach ($result as $user) {
            array_push($users, new User($user['id'], $user['name'], $user['email']));
        }
        return $users;
    }

    /**
     * Creates a new empty User instance and also injects the data access object instance.
     *
     * @param UserInterface $userRepository - Instance of the data access object
     * which proxies all the DB operations.
     * @return User - Empty User object.
     */
    public static function initialize(UserInterface $userRepository)
    {
        self::$userRepository = $userRepository;
        return new User(null, null, null);
    }

    /**
     * Validates abd saves the User info by proxying the create and update
     * DB operations on data access object.
     *
     * @return bool - false if either the user data is not valid or the save is not successful, or else true.
     */
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

    /**
     * Proxies the user deletion operation on data access object.
     *
     * @return bool - true if the user is deleted successfully, or else false.
     */
    public function destroy()
    {
        return $this->getUserRepository()->destroy($this->id);
    }

    /**
     * Validates the user object.
     *
     * @return bool - true if the user data is valid or else false.
     */
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

    /**
     * Returns the UserInterface static instance.
     *
     * @return UserInterface instance
     */
    private function getUserRepository()
    {
        return self::$userRepository;
    }
}
