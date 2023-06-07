<?php
require 'vendor/autoload.php';
include_once 'DB/Connection.php';
include_once 'oop/users.php';
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


$rate=[];
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
foreach ($obj as $item){
    if($item['Code'] == 'USD' || $item['Code'] == 'EUR' || $item['Code'] == 'GBP' || $item['Code'] == 'AUD'){
        $rate[] = $item;
    }
}
return $rate;
