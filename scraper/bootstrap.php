<?php

date_default_timezone_set('Europe/Belgrade');

require_once 'vendor/autoload.php';

use App\Infrastructure\DBConnection;
use Dotenv\Dotenv;

$dotenv = new Dotenv(dirname(__FILE__) . '/../laravel');
$dotenv->load();

DBConnection::getConnection([
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'dbname' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8'
]);

function env($var) {
    return getenv($var);
}