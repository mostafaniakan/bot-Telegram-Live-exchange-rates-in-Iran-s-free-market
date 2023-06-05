<?php
require 'vendor/autoload.php';
include 'DB/Conection.php';
use Goutte\Client;

$results = array();




    $client = new Client();
    $crawler = $client->request('GET', 'https://bonbast.com/archive');
    $crawler->filter('.table ')->each(function ($item)use (&$results) {
        $item->filter('tr')->each(function ($tr)use (&$results) {
            $tr->filter('td')->each(function ($td)use (&$results) {
                $row = $td->text();
                array_push($results,$row) ;
            });
        });
    });



$obj =[];
for ($i = 0; $i + 4 < count($results); $i += 4) {

    if ($i > 3) {
        $obj[] = [
            $results[0] => $results[$i],
            $results[1] => $results[$i + 1],
            $results[2] => $results[$i + 2],
            $results[3] => $results[$i + 3],
        ];
    }
}

//$sql = "INSERT INTO items_users (user_id, value) VALUES (?,?)";
//$stmt = $db->prepare($sql);
//$stmt->execute([$chat_id, strtoupper($value



for ( $x=0;$x<count($obj);$x++){
    if($obj[$x]['Code']=='EUR'){
        $sql = "INSERT INTO `rates`( `name`, `buy`, `sell`) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$obj[$x]['Code'],$obj[$x]['Buy'],$obj[$x]['Sell']]);
    }
    if($obj[$x]['Code']=='USD'){
        $sql = "INSERT INTO `rates`( `name`, `buy`, `sell`) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$obj[$x]['Code'],$obj[$x]['Buy'],$obj[$x]['Sell']]);

    }
    if($obj[$x]['Code']=='GBP'){
        $sql = "INSERT INTO `rates`( `name`, `buy`, `sell`) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$obj[$x]['Code'],$obj[$x]['Buy'],$obj[$x]['Sell']]);

    }
    if($obj[$x]['Code']=='AUD'){
        $sql = "INSERT INTO `rates`( `name`, `buy`, `sell`) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$obj[$x]['Code'],$obj[$x]['Buy'],$obj[$x]['Sell']]);
    }
}
return $obj;