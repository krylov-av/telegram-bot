<?php
$dir = __dir__;

require_once $dir.'/../../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable($dir.'/../../');
$dotenv->load();

$url = 'https://api.telegram.org/bot';
$token = env('TELEGRAM_BOT_TOKEN');

while (true)
{
    $json = file_get_contents($url.$token.'/getUpdates?offset='.$offset);
    $data = json_decode($json,true);
    foreach ($data['result'] as $message)
    {
        $offset = $message['update_id'] + 1;
        $chatId = $message['message']['chat']['id'];
        $text = $message['message']['text'];
        //print "Offset=".$offset."\r\n";
        //print "chatId=".$chatId."\r\n";
        //print "text=".$text."\r\n";
        ////Reply
        $data = file_get_contents($url.$token.'/sendMessage?chat_id='.$chatId.'&text=Ok');
        print $data;

    }
    sleep(5);
}
