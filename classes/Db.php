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
                $host = 'junction.proxy.rlwy.net'; // Hostname of the server (Railway)
                $dbname = 'Onlinestore'; // Database name
                $user = 'root'; // Username for the database
                $pass = 'iQwnTPGMUxJIiDRHaTMDRXCgjPAwzNxd'; // Password for the database
                $port = 35348; // Port number

                self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //echo "✅ Verbinding gemaakt!";
                return self::$conn;
            } catch (PDOException $e) {
                echo "❌ Verbinding mislukt: " . $e->getMessage();
                return null;
            }
        } else {
            //echo "✅ Verbinding hergebruikt!";
            return self::$conn;
        }
    }
}
?>
