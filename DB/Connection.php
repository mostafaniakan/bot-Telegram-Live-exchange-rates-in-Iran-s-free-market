<?php
namespace DB;
use PDO;

$hostName="localhost";
$user="root";
$pass="";
$dbName="telegram_bot";
$DNS="mysql:host=$hostName;dbname=$dbName;charset:utf8";
$db = new PDO($DNS, $user, $pass);