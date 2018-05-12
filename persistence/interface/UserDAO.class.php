<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 5/11/2018
 * Time: 12:34 PM
 */

interface UserDAO{
    /**
     * @param User $user
     */
    public function create (User $user);

    /**
     * @param User $user
     */
    public function authenticate(User $user);
}