<?php
namespace Fienwouters\Onlinestore\interfaces;

interface iProduct {
    public function getId();
    public function setId($id);
    public function getName();
    public function setName($name);
    public function getDescription();
    public function setDescription($description);
    public function getPrice();
    public function setPrice($price);
    public function getStock();
    public function setStock($stock);
    public function getCategoryId();
    public function setCategoryId($categoryId);
    public function getImage();
    public function setImage($image);
    public function save();
    public function fetch();
    public static function search($searchTerm);
    public static function delete($id);
}
?>