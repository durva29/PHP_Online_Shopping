<?php
    // Update the details below with your MySQL details
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'onlineshop';

function pdo_connect_mysqli() {
    try {
    	return mysqli_connect($GLOBALS["DATABASE_HOST"], $GLOBALS["DATABASE_USER"], $GLOBALS["DATABASE_PASS"], $GLOBALS["DATABASE_NAME"]);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}

function pdo_connect_mysql() {
    try {
    	// return mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        return new PDO('mysql:host=' . $GLOBALS["DATABASE_HOST"] . ';dbname=' . $GLOBALS["DATABASE_NAME"] . ';charset=utf8', $GLOBALS["DATABASE_USER"], $GLOBALS["DATABASE_PASS"]);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}

?>