<?php
namespace DB;
use PDO;

$hostName="localhost";
$user="root";
$pass="";
$dbName="telegram_bot";
define("DSN","mysql:host=$hostName;dbname=$dbName;charset:utf8");
define("db_user","$user");
define("db_pass","$pass");
$db = new PDO(DSN, db_user, db_pass);