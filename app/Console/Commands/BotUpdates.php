<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BotUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://api.telegram.org/bot';
        $token = env('TELEGRAM_BOT_TOKEN');

        $json = file_get_contents($url.$token.'/getUpdates');
        $data = json_decode($json,true);
        if (count($data['result'])>0) {
            foreach ($data['result'] as $message) {
                $update_id = $message['update_id'];
                $from_id = $message['message']['from']['id'];
                if (!$telegramUser = \App\Models\TelegramUser::find($from_id)) {
                    //Create use
                    $telegramUser = new \App\Models\TelegramUser;
                    $telegramUser->id = $from_id;
                    $telegramUser->first_name = $message['message']['from']['first_name'];
                    $telegramUser->last_name = $message['message']['from']['last_name'];
                    $telegramUser->language_code = $message['message']['from']['language_code'];
                    $telegramUser->is_bot = $message['message']['from']['is_bot'];
                    $telegramUser->chat_id = $message['message']['chat']['id'];
                    $telegramUser->type = $message['message']['chat']['type'];
                    $telegramUser->save();
                } else {
                    //update info about user;
                    $telegramUser->first_name = $message['message']['from']['first_name'];
                    $telegramUser->last_name = $message['message']['from']['last_name'];
                    $telegramUser->language_code = $message['message']['from']['language_code'];
                    $telegramUser->save();
                }
                //Pass incoming message to log
                $telegramMessage = new \App\Models\TelegramChat;
                //$telegramMessage->id = $update_id;
                $chatId = $message['message']['chat']['id'];
                $telegramMessage->chat_id = $chatId;
                $telegramMessage->date = $message['message']['date'];
                $telegramMessage->direction = 'in';
                $text = $message['message']['text'] ?? null;
                $telegramMessage->text = $text;
                $telegramMessage->save();

                if (is_numeric($text))
                {
                    //Try to find order
                    if (!$Order = \App\Models\Order::find($text))
                    {
                        //unknown order
                        $json = file_get_contents($url.$token.'/sendMessage?chat_id='.$chatId.'&text=Unknown Order');
                        $data = json_decode($json,true);
                        $telegramMessage = new \App\Models\TelegramChat;
                        $telegramMessage->chat_id = $chatId;
                        $telegramMessage->date = $data['result']['date'];
                        $telegramMessage->direction = 'out';
                        $telegramMessage->text=$data['result']['text'];
                        $telegramMessage->save();
                    }
                    else
                    {
                        //   "id" => 22
                        //    "user_id" => 1
                        //    "title" => "Gorgeous Steel Keyboard"
                        //    "clientName" => "Ilene Green"
                        //    "status" => 0
                        //    "created_at" => "2020-09-22 10:00:38"
                        //    "updated_at" => "2020-09-22 10:00:38"
                        $out = 'Order: '.$Order->id."\r\n";
                        $out.= 'Manager: '.$Order->user->name."\r\n";
                        $out.= 'Title: '.$Order->title."\r\n";
                        $out.= 'clientName: '.$Order->clientName."\r\n";
                        $out.= 'Status: '.(($Order->status===0)?"<b>In process</b>":"Done")."\r\n";
                        $out.= 'Updated at: <i>'.\Carbon\Carbon::parse($Order->updated_at)->format('d.m.Y').'</i>';
                        $send = urlencode($out);
                        $json = file_get_contents($url.$token.'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML'.'&text='.$send);
                        $data = json_decode($json,true);
                        //print_r($data);

                        $telegramMessage = new \App\Models\TelegramChat;
                        $telegramMessage->chat_id = $chatId;
                        $telegramMessage->date = $data['result']['date'];
                        $telegramMessage->direction = 'out';
                        $telegramMessage->text=$data['result']['text'];
                        $telegramMessage->save();
                    }
                }

                ////Reply
                //$data = file_get_contents($url.$token.'/sendMessage?chat_id='.$chatId.'&text=Ok');
                //print $data;

                //print_r($message);
                //print $from_id;
                //print_r($telegramUser);
                //print "========================================";
            }
            $json = file_get_contents($url . $token . '/getUpdates?offset=' . ($update_id + 1));
        }
        return 0;
    }
}
/*
 * "update_id" => 172422796
    "message" => array:5 [▼
      "message_id" => 39
      "from" => array:5 [▼
        "id" => 535305796
        "is_bot" => false
        "first_name" => "Oksana"
        "last_name" => "Krylova"
        "language_code" => "ru"
      ]
      "chat" => array:4 [▼
        "id" => 535305796
        "first_name" => "Oksana"
        "last_name" => "Krylova"
        "type" => "private"
      ]
      "date" => 1600747924
      "text" => "Привет"
    ]
 */
