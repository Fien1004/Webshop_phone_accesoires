<?php
namespace Fienwouters\Onlinestore\Interfaces;

interface iUser {
    public function getFirstname();
    public function setFirstname($firstname);
    public function getLastname();
    public function setLastname($lastname);
    public function getEmail();
    public function setEmail($email);
    public function getPassword();
    public function setPassword($password);
    public function save(); 
    public static function getAll(); 
    public static function emailExists($email);
}

