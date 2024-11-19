<?php
require_once __DIR__ . '/vendor/autoload.php';

use Fienwouters\Onlinestore\Db;

session_start();

// Foutmeldingen inschakelen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);