<?php

namespace service;
interface AnnonceAccessInterface
{
    public function getAllAnnonces();

    public function getPost($id);
}