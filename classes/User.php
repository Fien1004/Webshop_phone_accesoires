<?php
namespace Fienwouters\Onlinestore;

use \Fienwouters\Onlinestore\Interfaces\iUser;
use PDO;
use PDOException;

class User implements iUser {
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        if (empty($firstname)) {
            throw new Exception('Firstname is required');
        }
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        if (empty($lastname)) {
            throw new Exception('Lastname is required');
        }
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (empty($email)) {
            throw new Exception('Email is required');
        }
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        if (empty($password)) {
            throw new Exception('Password is required');
        }
        $options = ['cost' => 12];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
        return $this;
    }

    public function save() {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare('INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)');
            $statement->bindValue(':firstname', $this->firstname);
            $statement->bindValue(':lastname', $this->lastname);
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            return $statement->execute();
        } catch (PDOException $e) {
            throw new \Exception('Failed to save user: ' . $e->getMessage());
        }
    }

    public static function getAll() {
        $conn = Db::getConnection();
        $statement = $conn->query('SELECT * FROM users');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function emailExists($email) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        return $statement->fetchColumn() > 0;
    }

    //voeg canLogin toe
    public static function canLogin($email, $password) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT * FROM users WHERE email = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user; // Geeft de gebruiker terug als de inloggegevens correct zijn
            } else { 
                return false; // Geeft false terug als de inloggegevens onjuist zijn 
                }
        }
    }
}













/*
class User {
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        if (empty($firstname)) {
            throw new Exception('Firstname is required');
        }
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        if (empty($lastname)) {
            throw new Exception('Lastname is required');
        }
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (empty($email)) {
            throw new Exception('Email is required');
        }
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        if (empty($password)) {
            throw new Exception('Password is required');
        }
        $options = ['cost' => 12];
        $this->password = password_hash($password, PASSWORD_DEFAULT, $options);
        return $this;
    }

    public function save()
    {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare('INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)');
            $statement->bindValue(':firstname', $this->firstname);
            $statement->bindValue(':lastname', $this->lastname);
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            return $statement->execute();
        } catch (PDOException $e) {
            throw new Exception('Failed to save user: ' . $e->getMessage());
        }
    }

    public static function getAll()
    {
        $conn = Db::getConnection();
        $statement = $conn->query('SELECT * FROM users');
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function emailExists($email)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $statement->bindValue(':email', $email);
        $statement->execute();
        return $statement->fetchColumn() > 0; // Geeft true terug als de e-mail bestaat
    }
}*/
?>
