<?php

include 'Telegram.php';

date_default_timezone_set('asia/tehran');
/*
* address to change webhook
* https://api.telegram.org/botTOKEN/setwebhook?url=
*/
// Set the bot TOKEN
$bot_token = '';
// Instances the class
$telegram = new Telegram($bot_token);

/* If you need to manually take some parameters
*  $result = $telegram->getData();
*  $text = $result["message"] ["text"];
*  $chat_id = $result["message"] ["chat"]["id"];
*/

// Take some parameters
$text = $telegram->Text();
$user_id = $telegram->UserID();
$chat_id = $telegram->ChatID();
$user_name = $telegram->Username();
$first_name = $telegram->FirstName();
$last_name = $telegram->LastName();
$gtable = str_replace('-', 'g', "$chat_id");
$message_id = $telegram->MessageID();
$caption = $telegram->Caption();
$gtitle = $telegram->messageFromGroupTitle();
$reply_id = $telegram->ReplyToMessageID();
$messagea = $telegram->getData();
$get_chat = $telegram->getChat(['chat_id' => $chat_id]);
$chat_admin = $telegram->getChatAdministrators(['chat_id' => $chat_id]);
$usercanuse = array('bot_command', 'bold', 'italic', 'code', 'pre');
$content1 = ['chat_id' => $chat_id, 'user_id' => $user_id];
$jinfo = $telegram->getChatMember($content1);
$userstat = $jinfo['result']['status'];
$baseinfo = array(
    'delflag' => '0',
    'title' => "$gtitle",
    'link' => '',
    'rules' => '',
    'welcome' => '0',
);
// first use of bot!
if ($text == '/install' && ($userstat == 'creator' || $userstat == 'administrator')) {
    if (!file_exists("jsons/$gtable.json")) {
        file_put_contents("jsons/$gtable.json", json_encode($baseinfo, JSON_PRETTY_PRINT));
        $content = ['chat_id' => $chat_id, 'text' => 'سلام، تنظیمات با موفقیت ذخیره شدند و ربات آماده به کار است!'];
        $telegram->sendMessage($content);
    } else {
        $content = ['chat_id' => $chat_id, 'text' => 'تنظیمات از قبل موجود است!'];
        $telegram->sendMessage($content);
    }
}

if ($text == '/start') {
    $reply = 'Welcome to GNU/Linux Bot BY Jamal Yarali - Version 1.0.0';
    $content = ['chat_id' => $chat_id, 'text' => $reply];
    $telegram->sendMessage($content);
}
// report to moderators
if ($text == '/report') {
    if (isset($messagea['message']['reply_to_message'])) {
        $reported_message_id = $messagea['message']['reply_to_message']['message_id'];
        $reported_user_id = $messagea['message']['reply_to_message']['from']['id'];
        $reported_message_username = $messagea['message']['reply_to_message']['from']['username'];
        foreach ($get_admin['result'] as $admin_object) {
            $admin_bot = $admin_object['user']['is_bot'];
            if (!$admin_bot) {
                $admin_id = $admin_object['user']['id'];
                $content = ['chat_id' => $admin_id, 'from_chat_id' => $chat_id, 'message_id' => $reported_message_id];
                $telegram->forwardMessage($content);
                $content2 = ['chat_id' => $admin_id, 'text' => "پیام فوق توسط کاربر @$user_name با شناسه $user_id از گروه $gtitle گزارش شده است."];
                $telegram->sendMessage($content2);
            }
        }
    } else {
        $content = ['chat_id' => $chat_id, 'reply_to_message_id' => $message_id, 'text' => 'برای گزارش، روی پیام مورد نظر رپلای کنید!'];
        $telegram->sendMessage($content);
    }
}

// function for sending messages
function command($dastoor, $jtext)
{
    global $text,$chat_id,$reply_id,$bot_token;
    $telegram2 = new Telegram($bot_token);
    if ($text == $dastoor) {
        $reply = $jtext;
        $content = [
            'chat_id' => $chat_id,
            'reply_to_message_id' => $reply_id,
            'parse_mode' => 'HTML',
            'text' => $reply,
        ];

        return $telegram2->sendMessage($content);
    }
}

command('/smart', 'https://wiki.ubuntu.ir/wiki/Smart_Questions');
command('/hacker', 'http://linuxbook.ir/chapters/being_hacker.html');
command('/bitcoin', 'https://bitcoin.org/fa/faq');
command('/lamp', '<pre>sudo apt install lamp-server^</pre> نرم‌افزارهایی مانند XAMPP برای پیاده‌سازی LAMP داخل سیستم‌عامل‌هایی غیر از گنو-لینوکس توسعه داده شده‌اند و استفاده از آن‌ها در گنو-لینوکس به دلایل متعددی پیشنهاد نمی‌شود. پیشنهاد ما استفاده از LAMP است.    ');
command('/grub', 'https://wiki.ubuntu.ir/wiki/Grub/Recover');
command('/ask', 'لطفا بجای اینکه بپرسید که بعد سوال اصلی خود را بپرسید، مستقیما سوال اصلی خود را بپرسید :)');
command('/kali', 'پیشنهاد ما به شما، استفاده از یک توزیع معقول مثل اوبونتو است. در این صورت، مشکلات کمتری نیز خواهید داشت.');
command('/flood', 'لطفا از پخش کردن مطلب خود در چندین پست خودداری کرده و مطلب خود را مستقیما در یک پست بنویسید :)');
command('/xampp', 'نرم‌افزارهایی مانند XAMPP برای پیاده‌سازی LAMP داخل سیستم‌عامل‌هایی غیر از گنو-لینوکس توسعه داده شده‌اند و استفاده از آن‌ها در گنو-لینوکس به دلایل متعددی پیشنهاد نمی‌شود. پیشنهاد ما استفاده از LAMP است.');

// help menu is not complete
if ($text == '/help') {
    $reply = '/help
نمایش منوی راهنما

/install
شروع کار با ربات در گروه(ادمین)

/kick
اخراج کاربر با ریپلای(ادمین)

 
/link
نمایش لینک گروه
 
/delete 10
پاک کردن آخرین 10 پیام ارسال شده در گروه(ادمین)

/setrules rules...
تنظیم قوانین گروه(ادمین)

/rules
نمایش قوانین گروه

/delon
فعال سازی حذف لینک در گروه(ادمین)

/deloff
غیر فعال سازی حذف لینک ها در گروه(ادمین)

/stickeron
فعالسازی حذف استیکرهای ارسالی در گروه(ادمین)

/stickeroff
غیرفعال کردن حذف استیکرهای ارسالی در گروه(ادمین)
';
    $content = [
        'chat_id' => $chat_id,
        'text' => $reply,
    ];
    $telegram->sendMessage($content);
}
// kick out users by reply user message!
if ($text == '/kick' && ($userstat == 'creator' || $userstat == 'administrator' )) {
    $kick_id = $messagea['message']['reply_to_message']['from']['id'];
    $kick_username = $messagea['message']['reply_to_message']['from']['username'];

    $content = ['chat_id' => $chat_id, 'user_id' => $kick_id];
    $telegram->kickChatMember($content);
    $content = [
        'chat_id' => $chat_id,
        'text' => "کاربر $kick_username به دلیل عدم رعایت قوانین اخراج شد.",
    ];
    $telegram->sendMessage($content);
}

// save group link
if (strpos($text, '/setlink') !== false) {
    $reply = str_replace('/setlink', '', $text);

    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['link'] = $reply;
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = [
        'chat_id' => "$chat_id",
        'text' => 'لینک گروه با موفقیت ذخیره شد.',
    ];
    $telegram->sendMessage($content);
}
// show saved group link
if ($text == '/link' || $text == 'لینک گروه') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $link = $data['link'];
    $content = [
        'chat_id' => $chat_id,
        'text' => $link,
        'reply_to_message_id' => $message_id,
    ];
    $telegram->sendMessage($content);
}
// send welcome message to new users (if enabled) - auto remove bots
if (isset($messagea['message']['new_chat_members'])) {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $welcome = $data['welcome'];
    foreach ($messagea['message']['new_chat_members'] as $wel_id) {
        if ($wel_id['is_bot'] == false && $welcome == 1) {
            $wel_name = $wel_id['first_name'];
            $content = [
                'chat_id' => $chat_id,
                'text' => " سلام $wel_name! به گروه $gtitle خوش آمدید. ",
            ];
            $telegram->sendMessage($content);
        } elseif ($wel_id['is_bot'] == true) {
            $kick_bot_id = $wel_id['id'];
            $content = [
                'chat_id' => $chat_id,
                'user_id' => $kick_bot_id,
            ];
            $telegram->kickChatMember($content);
        }
    }
    // sleep(10);
    // $content2 = [
    //         'chat_id' => $chat_id,
    //         'message_id' => $message_id + 1
    // ];
    // $telegram->deleteMessage($content2);
}
// delete messages
if (strpos($text, '/delete') !== false && ($userstat == 'creator' || $userstat == 'administrator')) {
    $delnum0 = str_replace('/delete', '', $text);
    $delnum = (int) $delnum0;
    $content = [
        'chat_id' => $chat_id,
        'text' => "deleting $delnum messages ... ",
    ];
    $telegram->sendMessage($content);
    sleep(2);
    $delids = $message_id - $delnum;
    for ($i = $message_id + 1; $i >= $delids; --$i) {
        $content = [
            'chat_id' => $chat_id,
            'message_id' => $i,
        ];
        $telegram->deleteMessage($content);
        sleep(0.25);
    }
}
// save group rules
if (strpos($text, '/setrules') !== false && ($userstat == 'creator' || $userstat == 'administrator')) {
    $rules0 = str_replace('/setrules', '', $text);
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['rules'] = $rules0;
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = [
        'chat_id' => $chat_id,
        'text' => 'قوانین با موفقیت ذخیره شد!',
        'reply_to_message_id' => $message_id,
    ];
    $telegram->sendMessage($content);
}
// show saved group rules
if ($text == '/rules') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $rules = $data['rules'];
    $content = [
        'chat_id' => $chat_id,
        'text' => $rules,
        'reply_to_message_id' => $message_id,
    ];
    $telegram->sendMessage($content);
}
// siwtch on for deleting links, hashtags, mentions (message entities) . you can set in $usercanuse variable what MessageEntities users are able to use.
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/delon') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['delflag'] = '1';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = ['chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'text' => 'Delete Links is *on*'];
    $telegram->sendMessage($content);
}
// switch on for deleting stickers.
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/stickeron') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['sticker'] = '1';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = ['chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'text' => 'Delete sticker is *on*'];
    $telegram->sendMessage($content);
}
// switch off for delete messages that contain entities ...
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/deloff') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['delflag'] = '0';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = ['chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'text' => 'Delete Links is *off*'];
    $telegram->sendMessage($content);
}
// switch off for deleting stickers. users are allowed to send stickers
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/stickeroff') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['sticker'] = '0';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = ['chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'text' => 'Delete Sticker is *off*'];
    $telegram->sendMessage($content);
}
// trigger for delete stickers
if (isset($messagea['message']['sticker']) && !($userstat == 'creator' || $userstat == 'administrator')) {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $stickerd = $data['sticker'];
    if ($stickerd == 1) {
        $content = [
        'chat_id' => $chat_id,
        'message_id' => $message_id,
    ];
        $telegram->deleteMessage($content);
    }
}
// trigger for delete message entities
if (isset($messagea['message']['entities'])) {
    foreach ($messagea['message']['entities'] as $jenti) {
        $fmatch = $jenti['type'];
        if (!in_array($fmatch, $usercanuse) && !($userstat == 'creator' || $userstat == 'administrator')) {
            $jsonString = file_get_contents("jsons/$gtable.json");
            $data = json_decode($jsonString, true);
            $delflag = $data['delflag'];
            if ($delflag == 1) {
                $content = [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                    ];
                $telegram->deleteMessage($content);
            }
        }
    }
}
// trigger for delete caption entities
if (isset($messagea['message']['caption_entities'])) {
    foreach ($messagea['message']['caption_entities'] as $jenti) {
        $fmatch = $jenti['type'];
        if (!in_array($fmatch, $usercanuse) && !($userstat == 'creator' || $userstat == 'administrator')) {
            $jsonString = file_get_contents("jsons/$gtable.json");
            $data = json_decode($jsonString, true);
            $delflag = $data['delflag'];
            if ($delflag == 1) {
                $content = [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                    ];
                $telegram->deleteMessage($content);
            }
        }
    }
}
// you can define bad words here!
if (strpos($text.$caption, 'سکس') !== false) {
    if (!($userstat == 'creator' || $userstat == 'administrator')) {
        $jsonString = file_get_contents("jsons/$gtable.json");
        $data = json_decode($jsonString, true);
        $delflag = $data['delflag'];
        if ($delflag == 1) {
            $content = [
                'chat_id' => $chat_id,
                'message_id' => $message_id,
            ];
            $telegram->deleteMessage($content);
        }
    }
}
// switch ON for send welcome message  to new users
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/welon') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['welcome'] = '1';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = [
        'chat_id' => $chat_id,
        'parse_mode' => 'Markdown',
        'text' => 'Welcome message *on*',
    ];
    $telegram->sendMessage($content);
}
// switch OFF for send welcome message  to new users
if (($userstat == 'creator' || $userstat == 'administrator') && $text == '/weloff') {
    $jsonString = file_get_contents("jsons/$gtable.json");
    $data = json_decode($jsonString, true);
    $data['welcome'] = '0';
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("jsons/$gtable.json", $newJsonString);
    $content = [
        'chat_id' => $chat_id,
        'parse_mode' => 'Markdown',
        'text' => 'Welcome message *off*',
    ];
    $telegram->sendMessage($content);
}
// ubuntu farsi spell correction :)))
if (strpos($text.$caption, 'ابنتو') !== false || strpos($text.$caption, 'اوبنتو') !== false || strpos($text.$caption, 'ابونتو') !== false) {
    $reply = '*اوبونتو';
    $content = [
        'chat_id' => $chat_id,
        'reply_to_message_id' => $message_id,
        'parse_mode' => 'HTML',
        'text' => $reply,
    ];
    $telegram->sendMessage($content);
}
