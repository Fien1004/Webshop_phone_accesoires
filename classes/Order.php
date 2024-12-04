<?php
namespace Fienwouters\Onlinestore;

use PDO;
use PDOException;
use Exception;

class Order {
    private $db;
    private $user_id;

    public function __construct($user_id) {
        $this->db = Db::getConnection();
        $this->user_id = $user_id;
    }

    public function getOrders() {
        try {
            $stmt = $this->db->prepare("
                SELECT o.id, o.total_price, o.order_date, u.firstname, u.lastname, u.email, u.address, u.postal_code, u.city, u.country,
                       oi.product_id, p.product_name, oi.quantity, oi.product_type, p.unit_price
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN order_items oi ON o.id = oi.order_id
                JOIN products p ON oi.product_id = p.id
                WHERE o.user_id = :user_id
            ");
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Fout bij het ophalen van bestellingen: " . $e->getMessage());
        }
    }

    public function placeOrder($cart_items, $total, $wallet) {
        try {
            // Controleer of de gebruiker voldoende saldo heeft
            if ($wallet < $total) {
                throw new Exception("Onvoldoende saldo om de bestelling te plaatsen.");
            }

            // Begin een transactiesessie
            $this->db->beginTransaction();

            // Werk het saldo van de gebruiker bij
            $new_wallet = $wallet - $total;
            $stmt = $this->db->prepare("UPDATE users SET wallet = :wallet WHERE id = :id");
            $stmt->bindValue(':wallet', $new_wallet, PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
            $stmt->bindValue(':id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Sla de bestelling op in de orders-tabel
            $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':total_price', $total, PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
            $stmt->execute();
            $order_id = $this->db->lastInsertId();

            // Sla de order_items op
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, product_type) VALUES (:order_id, :product_id, :quantity, :unit_price, :product_type)");
            foreach ($cart_items as $item) {
                $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->bindValue(':product_id', $item['product_id'], PDO::PARAM_INT);
                $stmt->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindValue(':unit_price', $item['unit_price'], PDO::PARAM_STR); // Gebruik PDO::PARAM_STR voor nauwkeurigheid
                $stmt->bindValue(':product_type', $item['product_type'], PDO::PARAM_STR);
                $stmt->execute();
            }

            // Leeg het winkelmandje
            $stmt = $this->db->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->execute();

            // Commit de transactiesessie
            $this->db->commit();

            return "Bestelling succesvol geplaatst!";
        } catch (Exception $e) {
            // Rollback de transactiesessie bij een fout
            $this->db->rollBack();
            throw new Exception("Fout bij het plaatsen van de bestelling: " . $e->getMessage());
        }
    }
}