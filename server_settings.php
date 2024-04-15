<?php
const serverIp = "localhost";
const serverUser = "S5169562";
const password = "saw123456";
const serverName = "S5169562";

// Mostra gli warning nella fase di development, nasconde i warning nel server
ini_set('display_errors', FALSE); // Use TRUE in development, FALSE in production environment.

error_reporting(E_ALL); // Error/Exception engine, always use E_ALL
ini_set('ignore_repeated_errors', TRUE);
ini_set('log_errors', TRUE); // Error/Exception file logging engine.

// tutti gli errori vengono stampati in errors.log
ini_set('error_log', './errors.log'); // Logging file path
