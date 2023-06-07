<?php

namespace oop;

class btn
{
    public function inlineKeyboards($token, $chat_id, $text, $callback_data, $message)
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

        $data = http_build_query([
            'text' => $message,
            'chat_id' => $chat_id,
        ]);

        $url = "https://api.telegram.org/bot" . $token . "/sendMessage?{$data}&reply_markup={$keyboard}";
        $res = @file_get_contents($url);
    }

    public function customKeyboard($token, $chat_id, $message)
    {
        $keyboard = [
            'keyboard' => [
                ['USD', 'EUR '],
                ['GBP', 'AUD'],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ];

        $encodedKeyboard = json_encode($keyboard);
        $open = $message;
        file_get_contents("https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chat_id . "&text=" . $open . "&reply_markup=" . $encodedKeyboard);
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

// The Telegram API URL
        $url = "https://api.telegram.org/bot$token/sendMessage";

// The data to send to the API
        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $reply_markup,
        ];

// Send the message to the API
         file_get_contents($url . '?' . http_build_query($data));


    }
}