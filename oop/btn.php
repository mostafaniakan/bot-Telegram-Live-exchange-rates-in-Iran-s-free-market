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

    public function customKeyboard($db,$chat_id, $message)
    {
        $methods = new methods();
        $language=$methods->getLanguage($db,$chat_id);


        $btn1 = "USD";
        $btn2 = "EUR";
        $btn3 = "GBP";
        $btn4 = "AUD";
        if($language == 'ENGLISH'){
            $setting = "setting⚙️";
        }else if('PERSIAN'){
            $setting = "تنظیمات⚙️";
        }


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

        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
            'reply_markup' => $encodedKeyboard,
            'parse_mode' => 'html',

        ]);
    }

    public function getPhoneNumber($text, $chat_id)
    {

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
            'parse_mode' => 'html',
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
        $rate = $user->getUserRates($db, $chat_id);
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
            'message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
        bot('editMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $keyboard,
            'parse_mode' => 'html'
        ]);

    }

    public function SettingradioButton($db, $chat_id)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);

        if ($language == 'ENGLISH') {
            $btn1 = "select";
            $btn2 = "select Language";
            $text = "SETTING";
        } else {
            $btn1 = "انتخاب";
            $btn2 = "انتخاب زبان";
            $text = 'تنظیمات';
        }

        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ['text' => $btn1, 'callback_data' => 'AddRate'],
                    ['text' => $btn2, 'callback_data' => 'Selecting Language'],
                ],

            ]
        ]);


        $data = bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $keyboard,
            'parse_mode' => 'html',
        ]);
        $methods->updateButtonIdSetting($db, $data->result->message_id);
    }

    public function selectLanguage($chat_id, $text, $message_id)
    {
        $btn1 = "ENGLISH";
        $btn2 = "فارسی";

        $callback_data1 = "AddENGLISH";
        $callback_data2 = "AddPERSIAN";


        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ['text' => $btn1, 'callback_data' => $callback_data1],
                    ['text' => $btn2, 'callback_data' => $callback_data2],
                ]
            ]
        ]);

        bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id,
            'parse_mode' => 'html'
        ]);
        bot('editMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => $keyboard,
            'parse_mode' => 'html'
        ]);

    }

    public function languageNotif($db, $chat_id, $message_id)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);
        if ($language == 'ENGLISH') {
            $text = "Changes applied successfully";
        } else if ($language == 'PERSIAN') {
            $text = "تغییرات با موفیت اعمال شد";
        }

       bot('editMessageText', [
            'chat_id' => $chat_id,
            'text' => $text,
            'message_id' => $message_id,
            'parse_mode' => 'html'
        ]);

    }

    public function selectLanguageFirst($db, $chat_id)
    {
        $methods = new methods();
        $language = $methods->getLanguage($db, $chat_id);
        $text = "<pre>Please Select Your Language

لطفا زبان خود را انتخاب کنید
</pre>";

        $btn1 = "ENGLISH";
        $btn2 = "فارسی";


        $keyboard = json_encode([
            "inline_keyboard" => [
                [
                    ['text' => $btn1, 'callback_data' => 'firstENGLISH'],
                    ['text' => $btn2, 'callback_data' => 'firstPERSIAN'],
                ],

            ]
        ]);


        $data = bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => $keyboard,
            'parse_mode' => 'html',
        ]);
    }
}