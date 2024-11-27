<?php
use Fienwouters\Onlinestore\Review;
use Fienwouters\Onlinestore\Db;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once(__DIR__ . "/../classes/Review.php");
require_once(__DIR__ . "/../bootstrap.php");

if (!isset($_SESSION['user'])) {
    $response = [
        'status' => 'error',
        'message' => 'Gebruiker niet ingelogd.'
    ];
    echo json_encode($response);
    //var_dump($_SESSION);
    exit;
}


if (!empty($_POST)) {
    try {
        $text = $_POST['text'];  // Review tekst
        $rating = $_POST['rating'];  // Beoordeling
        $product_id = $_POST['product_id'];  // Product ID
        $user_id = $_SESSION['user']['id'];  // Gebruikers-ID uit de sessie
        $user_firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 'Anoniem';
    
        // Maak een nieuw Review object
        $review = new Review();
        $review->setText($text);
        $review->setRating($rating);
        $review->setProduct_id($product_id);
        $review->setUser_id($user_id);
        $review->setUser_firstname($user_firstname);
        
        // Sla de review op
        $review->save();

        // Stuur een succes-response
        $response = [
            'status' => 'success',
            'body' => htmlspecialchars($review->getText()), // Review tekst
            'rating' => $review->getRating(), // Rating
            'user_firstname' => htmlspecialchars($review->getUser_firstname()), // Gebruikersnaam
            'message' => 'Review toegevoegd!' // Succesbericht
        ];

    } catch (Exception $e) {
        // Stuur een fout-response
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];

        echo json_encode($response);
        header('Content-Type: application/json');
        exit;
    }
}
?>