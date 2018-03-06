<?php

ini_set( 'error_reporting', E_ALL ); // or error_reporting(E_ALL);
ini_set( 'display_errors', '1' );
ini_set( 'display_startup_errors', '1' );

define( 'ABSPATH', true );
define( 'TESTEE_PATH', dirname( __DIR__ ) . '/src' );

require_once __DIR__ . '/../vendor/autoload.php';