<?php
/**
 * Created by PhpStorm.
 * @author ayip
 * Date: 5/9/16
 * Time: 3:36 PM
 */

namespace application;

use mysqli;

/**
 * Class UserRepository - Implements UserInterface and is responsible
 * for database transactions with the users table.
 *
 * @package application
 * @subpackage Repository
 */
class UserRepository implements UserInterface
{
    /**
     * @var mysqli connection
     */
    private static $connection;

    /**
     * Creates singleton instance of mysqli connection to the database.
     *
     * @return mysqli
     */
    private static function getConnection()
    {
        $connection = self::$connection;
        if (!isset($connection)) {
            $connection = new mysqli('192.168.10.10:3306', 'homestead', 'secret', 'homestead');
        }
        return $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function create(User $user)
    {
        $connection = self::getConnection();
        $result = $connection->query("INSERT INTO users (name, email) VALUES ('$user->name','$user->email')");
        $user->id = $connection->insert_id;
        if ($result) {
            return $user;
        } else {
            echo "User creation failed.\n";
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function update(User $user)
    {
        $connection = self::getConnection();
        $query = "UPDATE users SET name='$user->name', email='$user->email' WHERE id=$user->id";
        $result = $connection->query($query);
        if (isset($result)) {
            echo "User updated successfully.\n";
            return true;
        } else {
            echo "User updation failed.\n";
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($id)
    {
        $result = self::getConnection()->query("DELETE FROM users WHERE id = $id");
        if (isset($result)) {
            echo "User deleted successfully.\n";
            return true;
        } else {
            echo "User deletion failed.\n";
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
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
        return $result->fetch_object();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
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
        return $result;
    }
}
