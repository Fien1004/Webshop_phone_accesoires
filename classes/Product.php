<?php
namespace Fienwouters\Onlinestore;

use Fienwouters\Onlinestore\interfaces\iProduct;
use PDO;
use PDOException;

class Product implements iProduct {
    private $id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $categoryId;
    private $image;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
        return $this;
    }

    public function getCategoryId() {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function save() {
        try {
            $conn = Db::getConnection();
            if ($this->id) {
                $statement = $conn->prepare("UPDATE products SET product_name = :name, discription = :description, unit_price = :price, stock = :stock, category_id = :categoryId, img = :image WHERE id = :id");
                $statement->bindValue(':id', $this->id);
            } else {
                $statement = $conn->prepare("INSERT INTO products (product_name, discription, unit_price, stock, category_id, img) VALUES (:name, :description, :price, :stock, :categoryId, :image)");
            }
            $statement->bindValue(':name', $this->name);
            $statement->bindValue(':description', $this->description);
            $statement->bindValue(':price', $this->price);
            $statement->bindValue(':stock', $this->stock);
            $statement->bindValue(':categoryId', $this->categoryId);
            $statement->bindValue(':image', $this->image);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception('Failed to save product: ' . $e->getMessage());
        }
    }

    public function fetch() {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM products WHERE id = :id");
            $statement->bindValue(':id', $this->id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to fetch product: ' . $e->getMessage());
        }
    }

    public function getProductTypes() {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM product_types WHERE product_id = :product_id");
            $statement->bindValue(':product_id', $this->id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to fetch product types: ' . $e->getMessage());
        }
    }

    public static function search($searchTerm) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("SELECT * FROM products WHERE product_name LIKE :searchTerm OR discription LIKE :searchTerm");
            $statement->bindValue(':searchTerm', '%' . $searchTerm . '%');
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to search products: ' . $e->getMessage());
        }
    }

    public static function delete($id) {
        try {
            $conn = Db::getConnection();
            $statement = $conn->prepare("DELETE FROM products WHERE id = :id");
            $statement->bindValue(':id', $id);
            $statement->execute();
        } catch (PDOException $e) {
            throw new Exception('Failed to delete product: ' . $e->getMessage());
        }
    }
}
?>