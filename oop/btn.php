<?php

namespace oop;


include_once('users.php');
include_once('methods.php');

class btn
{

    public function inlineKeyboards($chat_id, $text, $callback_data, $message)
    {

        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    [
                        "text" => $text,
                        "callback_data" => $callback_data,
                    ],

                ]

            ]

        ]);


        bot('sendMessage', [
            'reply_markup' => $keyboard,
            'text' => $message,
            'chat_id' => $chat_id
        ]);

    }

    public function customKeyboard($chat_id, $message)
    {


        $btn1 = "USD";
        $btn2 = "EUR";
        $btn3 = "GBP";
        $btn4 = "AUD";
        $setting = "setting⚙️";

        $keyboard = [

            'keyboard' => [
                [$btn1, $btn2],
                [$btn3, $btn4],
                [$setting],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ];

        $encodedKeyboard = json_encode($keyboard);
        $open = $message;
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $open,
            'reply_markup' => $encodedKeyboard

        ]);
    }

    public function getPhoneNumber($token, $chat_id)
    {
        $text = 'Please select share contact :';

// The keyboard with the "Share Contact" button
        $reply_markup = json_encode([
            'keyboard' => [
                [
                    [
                        'text' => 'Share Contact',
                        'request_contact' => true,

                    ],


                ],
            ],
            'resize_keyboard' => true,
        ]);


        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
        ]);

    }


    public function AddradioButton($db, $chat_id, $message_id, $text)
    {
        $btn1 = "USD";
        $btn2 = "EUR";
        $btn3 = "GBP";
        $btn4 = "AUD";
        $callback_data1 = "AddUSD";
        $callback_data2 = "AddEUR";
        $callback_data3 = "AddGBP";
        $callback_data4 = "AddAUD";

        $user = new users();
        $rate = $user->getUserRates($db);
        foreach ($rate as $item) {
            if ($item['name'] == 'USD') {
                $btn1 = "USD✔️";
                $callback_data1 = "DeleteUSD";
            }
            if ($item['name'] == 'EUR') {
                $btn2 = "EUR✔️";
                $callback_data2 = "DeleteEUR";
            }
            if ($item['name'] == 'GBP') {
                $btn3 = "GBP✔️";
                $callback_data3 = "DeleteGBP";
            }
            if ($item['name'] == 'AUD') {
                $btn4 = "AUD✔️";
                $callback_data4 = "DeleteAUD";
            }
        }
        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ['text' => $btn1, 'callback_data' => $callback_data1],
                    ['text' => $btn2, 'callback_data' => $callback_data2],
                ],
                [
                    ['text' => $btn3, 'callback_data' => $callback_data3],
                    ['text' => $btn4, 'callback_data' => $callback_data4],
                ]
            ]
        ]);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id
        ]);
        bot('editMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $keyboard
        ]);

    }

    public function SettingradioButton($db, $chat_id, $text)
    {
        $methods = new methods();
        $btn1 = "Add";
        $btn2 = "Delete";
        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ['text' => $btn1, 'callback_data' => 'AddRate'],
                ],

            ]
        ]);


        $data = bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $keyboard
        ]);
        $methods->updateButtonId($db, $data->result->message_id);
    }

    public function DeleteRadioButton($db, $id)
    {

    }
}