<?php

namespace service;

interface UserAccessInterface
{
    public function getUser($login, $password);
}