<?php
namespace Fienwouters\Onlinestore;

use PDO;
use PDOException;

class Db
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn == null) {
            try {
                self::$conn = new PDO('mysql:host=localhost;dbname=Onlinestore', 'root', '');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "✅ Verbinding gemaakt!";
                return self::$conn;
            } catch (PDOException $e) {
                echo "❌ Verbinding mislukt: " . $e->getMessage();
                return null;
            }
        } else {
            echo "✅ Verbinding hergebruikt!";
            return self::$conn;
        }
    }
}
?>
