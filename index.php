<?php

// Retrieve instance of the framework
$f3=require('lib/base.php');

// Initialize DB
$f3->config("app/config/db.ini");
// Initialize CMS
$f3->config('app/config/config.ini');

// Define routes
$f3->config('app/config/routes.ini');

// Execute application
$f3->run();
