<?php

namespace oop;




include('DB/Connection.php');
include('scraping.php');
include_once ('users.php');
include_once ('methods.php');
class updateRates
{

    public function updateRates($db,$rate,$obj,$chat_id)
    {

        $user = new users();
        $methods = new methods();

        for ($x = 0; $x < count($rate); $x++) {
            for ($i = 0; $i < count($obj); $i++) {
                if ($rate[$x]['name'] == 'USD' && $obj[$i]['Code'] == 'USD') {
                    if ($rate[$x]['buy'] == $obj[$i]['Buy'] && $rate[$x]['sell'] == $obj[$i]['Sell']) {

                    } else {
                         if($rate[$x]['buy'] < $obj[$i]['Buy']){
                             $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                             $methods->showRateUp($chat_id, $obj, $i);
                         }else{
                             $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                             $methods->showRatedown($chat_id, $obj,$i);
                         }
                    }
                }
            }

        }


        for ($x = 0; $x < count($rate); $x++) {
            for ($i = 0; $i < count($obj); $i++) {
                if ($rate[$x]['name'] == 'EUR' && $obj[$i]['Code'] == 'EUR') {
                    if ($rate[$x]['buy'] == $obj[$i]['Buy'] && $rate[$x]['sell'] == $obj[$i]['Sell']) {

                    } else {
                         $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                        $methods->showRate($chat_id, $obj, $i);
                    }
                }
            }

        }

        for ($x = 0; $x < count($rate); $x++) {
            for ($i = 0; $i < count($obj); $i++) {
                if ($rate[$x]['name'] == 'GBP' && $obj[$i]['Code'] == 'GBP') {
                    if ($rate[$x]['buy'] == $obj[$i]['Buy'] && $rate[$x]['sell'] == $obj[$i]['Sell']) {

                    } else {
                        $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                        $methods->showRate($chat_id, $obj, $i);
                    }
                }
            }

        }

        for ($x = 0; $x < count($rate); $x++) {
            for ($i = 0; $i < count($obj); $i++) {
                if ($rate[$x]['name'] == 'AUD' && $obj[$i]['Code'] == 'AUD') {
                    if ($rate[$x]['buy'] == $obj[$i]['Buy'] && $rate[$x]['sell'] == $obj[$i]['Sell']) {

                    } else {
                         $user->updateRate($db, $rate[$x]['id'], $obj[$i]['Buy'], $obj[$i]['Sell']);
                        $methods->showRate($chat_id, $obj, $i);
                    }
                }
            }

        }
    }



}