<?php
namespace Fienwouters\Onlinestore;

use \Fienwouters\Onlinestore\Interfaces\iUser;
use PDO;
use PDOException;

class User implements iUser {
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $address;
    private $postal_code;
    private $city;
    private $country;
    private $wallet;


    // Voeg getters en setters toe voor de id-eigenschap
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

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

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        if (empty($address)) {
            throw new Exception('Address is required');
        }
        $this->address = $address;
        return $this;
    }

    public function getPostalCode() {
        return $this->postal_code;
    }

    public function setPostalCode($postal_code) {
        if (empty($postal_code)) {
            throw new Exception('Postal code is required');
        }
        $this->postal_code = $postal_code;
        return $this;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        if (empty($city)) {
            throw new Exception('City is required');
        }
        $this->city = $city;
        return $this;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        if (empty($country)) {
            throw new Exception('Country is required');
        }
        $this->country = $country;
        return $this;
    }
    
    public function getWallet() {
        return $this->wallet;
    }

    public function setWallet($wallet) {
        if (empty($wallet)) {
            throw new Exception('Wallet is required');
        }
        $this->wallet = $wallet;
        return $this;
    }
        
    public function save() {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare('INSERT INTO users (firstname, lastname, email, password, address, postal_code, city, country) VALUES (:firstname, :lastname, :email, :password, :address, :postal_code, :city, :country)');
            $statement->bindValue(':firstname', $this->firstname);
            $statement->bindValue(':lastname', $this->lastname);
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            $statement->bindValue(':address', $this->address);
            $statement->bindValue(':postal_code', $this->postal_code);
            $statement->bindValue(':city', $this->city);
            $statement->bindValue(':country', $this->country);
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

    public function updatePassword($currentPassword, $newPassword) {
        // Haal de huidige gebruiker op uit de database
        $conn = Db::getConnection();
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception("Huidig wachtwoord is onjuist.");
        }
    
        // Hash het nieuwe wachtwoord
        $options = ['cost' => 12];
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT, $options);
    
        // Update het wachtwoord in de database
        $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindValue(":password", $hashedPassword);
        $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        return true;
    }
}
?>
