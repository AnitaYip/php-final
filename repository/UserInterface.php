<?php
/**
 * Created by PhpStorm.
 * User: ayip
 * Date: 5/9/16
 * Time: 3:35 PM
 */

namespace application;

interface UserInterface
{
    public function create(User $user);

    public function update(User $user);

    public function destroy($id);

    public function find($id);

    public function findAll();

}
