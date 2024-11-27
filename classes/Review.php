<?php

namespace Fienwouters\Onlinestore;

class Review{
    private $text;
    private $rating;
    private $product_id;
    private $user_id;
    private $user_firstname;
    

    /**
     * Get the value of text
     */ 
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @return  self
     */ 
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the value of rating
     */ 
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set the value of rating
     *
     * @return  self
     */ 
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get the value of product_id
     */ 
    public function getProduct_id()
    {
        return $this->product_id;
    }

    /**
     * Set the value of product_id
     *
     * @return  self
     */ 
    public function setProduct_id($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */ 
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
        
        return $this;
    }
    /**
     * Get the value of user_firstname
     */ 
    public function getUser_firstname()
    {
        return $this->user_firstname;
    }

    /**
     * Set the value of user_firstname
     *
     * @return  self
     */ 
    public function setUser_firstname($user_firstname)
    {
        $this->user_firstname = $user_firstname;

        return $this;
    }

    public function save(){
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO reviews (text, rating, product_id, user_id, user_firstname) VALUES (:text, :rating, :product_id, :user_id, :user_firstname)");            
            $text = $this->getText();
            $rating = $this->getRating();
            $product_id = $this->getProduct_id();
            $user_id = $this->getUser_id();
            $user_firstname = $this->getUser_firstname();


            $statement->bindValue(":text", $text);
            $statement->bindValue(":rating", $rating);
            $statement->bindValue(":product_id", $product_id);
            $statement->bindValue(":user_id", $user_id);
            $statement->bindValue(":user_firstname", $user_firstname);

            $result = $statement->execute();
            return $result;
    }

    public static function getAll($product_id){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM reviews WHERE product_id = :product_id");
        $statement->bindValue(":product_id", $product_id);
        $statement->execute();
        $result = $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    

}