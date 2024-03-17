<?php

namespace service;

class UserChecking
{
    public function authenticate($login, $password, $data)
    {
        return ($data->getUser($login, $password) != null);
    }

}