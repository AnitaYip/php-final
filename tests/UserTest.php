<?php

namespace application;

require_once(dirname(__FILE__).'/../model/User.php');
require_once(dirname(__FILE__).'/../vendor/autoload.php');

use \Faker\Factory as Faker;

/**
 * Created by PhpStorm.
 * User: ayip
 * Date: 5/6/16
 * Time: 12:08 PM
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    public function testWrites()
    {
        $numOfUsers = count(User::findAll());

        $faker = Faker::create();
        $newUser = new User(null, $faker->name, $faker->email);
        $result = $newUser->save();
        $this->assertNotFalse($result, "The result is false, the DB save failed.\n");
        $this->assertEquals(
            ($numOfUsers+1),
            count(User::findAll()),
            "The number of records in the database do not indicate successful data persistence"
        );

        $newName = $faker->name;
        $newEmail = $faker->email;
        $newUser->name = $newName;
        $newUser->email = $newEmail;

        $this->assertTrue($newUser->save(), "Update failed.\n");
        $modifiedUser = User::find($newUser->id);
        $this->assertNotNull($modifiedUser, "No record found with the given id.\n");
        $this->assertEquals($newName, $modifiedUser->name, "Name not updated correctly.\n");
        $this->assertEquals($newEmail, $modifiedUser->email, "Email not updated correctly. \n");

        $this->assertTrue($modifiedUser->destroy(), "User not deleted successfully.\n");
        $this->assertFalse(User::find($modifiedUser->id), "This user record should have been deleted.\n");

    }

    public function testValidation()
    {
        $faker = Faker::create();
        $user = User::initialize();
        // Validate empty User Object
        $this->validateEmptyUser($user);
        // Set name on User
        $user->name = $faker->name;
        // Validate user object with name field set
        $this->validateUserWithJustName($user);
        // Set email on user object
        $user->email = $faker->email;
        $this->validateValidUser($user);
    }

    private function validateValidUser(User $user)
    {
        $this->assertTrue($user->validate(), "Not a valid user");
    }

    private function validateEmptyUser(User $user)
    {
        $this->assertFalse($user->validate(), "User validation must fail on an empty user object.\n");
        $this->assertEquals(2, count($user->errors), "There should be 2 errors one for each field of User\n");
        $this->assertContains("Name", $user->errors[0], "Missing validation for name");
        $this->assertContains("Email", $user->errors[1], "Missing validation for email");
    }

    private function validateUserWithJustName(User $user)
    {
        $this->assertFalse($user->validate(), "User validation must fail on an empty fields in user object.\n");
        $this->assertEquals(1, count($user->errors), "There should be only 1 error for email field of User\n");
        $this->assertContains("Email", $user->errors[0], "Missing validation for email");
        foreach ($user->errors as $index1 => $error) {
            $this->assertFalse(strpos($error, 'name'), "Name field should not have a validation set. \n");
        }
    }
}
