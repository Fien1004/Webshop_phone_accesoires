<?php
namespace Fienwouters\Onlinestore;

use PDO;
use PDOException;

class Cart {
    private $db;
    private $user_id;

    public function __construct($user_id) {
         // Je database verbinding ophalen
        $this->db = Db::getConnection();
        $this->user_id = $user_id; // De user_id wordt meegegeven bij het aanmaken van de klasse
    }

    // Voeg product toe aan winkelmandje
    public function addProduct($product_id, $quantity) {
        $conn = Db::getConnection();
    
        // Controleer of het product al in het winkelmandje staat
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Als het product al bestaat, verhoog dan de hoeveelheid
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            // Anders voeg het product toe aan het winkelmandje
            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    // Verwijder product uit winkelmandje
    public function removeProduct($product_id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id");
            $stmt->bindValue(':user_id', $this->user_id);
            $stmt->bindValue(':product_id', $product_id);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Fout bij het verwijderen van product uit winkelmandje: " . $e->getMessage());
        }
    }

    // Haal alle producten uit het winkelmandje op
    public function getCartItems() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cart WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $this->user_id);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception("Fout bij het ophalen van winkelmandje items: " . $e->getMessage());
        }
    }

    
}
?>
