<?php

namespace oop;

use PDO;

class users
{
    public function checkUserId($db, $chat_id)
    {
        $sql = "SELECT `users_id` FROM `users` WHERE `users_id` = '$chat_id'";
        $stmt = $db->query($sql);
        $config = $stmt->rowCount();
        return $config;
    }

    public function createAcount($db, $chat_id, $username, $phones,)
    {
        $sql = "INSERT INTO users (users_id, name,phones) VALUES ('$chat_id','$username','$phones')";
        $stmt = $db->query($sql);
        $stmt->rowCount();
    }
    public function getUserRates($db){
        $data=[];
        $sql="SELECT * FROM `items_users` JOIN `rates` ON items_users.value = rates.name";
        $stmt = $db->query($sql);
        $row=$stmt->rowCount();
        if($row >0){
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
               array_push($data,$row);
            }
        }
       return $data;
    }
    public function updateRate($db,$id,$buy,$sell){
        $sql="UPDATE `rates` SET `buy`='$buy',`sell`='$sell' WHERE  `id` = $id";
        $stmt = $db->query($sql);
        $config=$stmt->rowCount();
        return $config;
    }
}