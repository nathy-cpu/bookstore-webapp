<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Don't initialize default request variables here
$_ENV['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');

// Initialize application components only
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../router.php';

// Initialize test database
require_once __DIR__ . '/TestDatabase.php';
TestDatabase::init();
