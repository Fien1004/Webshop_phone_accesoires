<?php
namespace Fienwouters\Onlinestore\interfaces;

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
    public function getAddress();
    public function setAddress($address);
    public function getPostalCode();
    public function setPostalCode($postal_code);
    public function getCity();
    public function setCity($city);
    public function getCountry();
    public function setCountry($country);
    public function getWallet();
    public function setWallet($wallet);
}
?>