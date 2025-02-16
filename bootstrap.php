<?php

require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Retrieve site URL from .env file
$siteUrl = $_ENV['SITE_URL']; // Now you can use $siteUrl throughout your application

// Database configuration using environment variables
$dbParams = [
    'driver'   => $_ENV['DB_DRIVER'],
    'host'     => $_ENV['DB_HOST'],
    'user'     => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname'   => $_ENV['DB_DATABASE'],
];

// Doctrine configuration
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . "/models"],
    isDevMode: true,
);

// Create EntityManager instance
$entityManager = new EntityManager(
    DriverManager::getConnection($dbParams, $config),
    $config
);