<?php

namespace oop;
class methods
{
    function bot($method, $datas = [])
    {
        $url = "https://api.telegram.org/bot" . token . "/" . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
        $res = curl_exec($ch);
        if (curl_error($ch)) {
            var_dump(curl_error($ch));
        } else {
            json_decode($res);
        }
    }
    public function sendmessage($chat_id, $text)
    {
        $this->bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'MarkDown'
        ]);
    }
    public function showRate($chat_id,$obj,$row){
        $this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code']."=>". 'Buy : ' . $obj[$row]['Buy'] ."=>". 'Sell : ' . $obj[$row]['Sell']);
//       $this->sendmessage($chat_id, 'Buy : ' . $obj[$row]['Buy']);
//       $this->sendmessage($chat_id, 'Sell : ' . $obj[$row]['Sell']);
    }
    public function showRateUp($chat_id,$obj,$row){
        $this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code'] . " => ". 'Buy : ' . $obj[$row]['Buy'] .'🟢' . " => " . 'Sell : ' . $obj[$row]['Sell'] . '🔴');
//        $this->sendmessage($chat_id, 'Buy : ' . $obj[$row]['Buy'] .'🟢');
//        $this->sendmessage($chat_id, 'Sell : ' . $obj[$row]['Sell'] . '🔴');
    }
    public function showRatedown($chat_id,$obj,$row){
        $this->sendmessage($chat_id, 'Name : ' . $obj[$row]['Code'] . " => ". 'Buy : ' . $obj[$row]['Buy'] .'🔴' . " => " . 'Sell : ' . $obj[$row]['Sell'] . '🟢');
//        $this->sendmessage($chat_id, 'Buy : ' . $obj[$row]['Buy'] . '🔴');
//        $this->sendmessage($chat_id, 'Sell : ' . $obj[$row]['Sell'] . '🟢' );
    }
}