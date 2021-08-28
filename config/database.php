<?php
$config = require_once './db_confing.php';

$driver = $config['driver'];
$host = $config['host'];
$dbname = $config['dbname'];
$username = $config['username'];
$password = $config['password'];

define('DSN', $driver.':host='.$host.';dbname='.$dbname);
define('USERNAME', $username);
define('PASSWORD', $password);