<?php
/**
 * Created by PhpStorm.
 * @author ayip
 * Date: 5/9/16
 * Time: 3:35 PM
 */

namespace application;

/**
 * Interface UserInterface - Responsible for providing function
 * definitions to perform CRUD operations with Users table.
 *
 * @package application
 * @subpackage Repository
 */
interface UserInterface
{
    /**
     * Inserts a new row in the users table.
     *
     * @param User $user - User model object
     * @return User|bool - DB result object if the row is inserted successfully or else false.
     */
    public function create(User $user);

    /**
     * Updates the user information in the users table.
     *
     * @param User $user - User model object with the data to be updated
     * @return bool - true if the data is updated successfully or else false.
     */
    public function update(User $user);

    /**
     * Deletes the record from users table.
     *
     * @param int $id Id of the user to be deleted
     * @return bool - true if the user is deleted successfully or else false.
     */
    public function destroy($id);

    /**
     * Fetches the user information for the give id from database if it exists.
     *
     * @param int $id Id of the user to retrieve
     * @return bool|object|\stdClass - user information if it exists or else false.
     */
    public function find($id);

    /**
     * Fetches all the users from database.
     *
     * @return bool|\mysqli_result - array of all the users from database or false if none found
     */
    public function findAll();
}
