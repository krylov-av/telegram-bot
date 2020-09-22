<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getMe',function(){
    $url = 'https://api.telegram.org/bot';
    $token = env('TELEGRAM_BOT_TOKEN');
    $json = file_get_contents($url.$token.'/getMe');
    $data = json_decode($json,true);
    dd($data);
});

Route::get('/getUpdates',function(){
    $url = 'https://api.telegram.org/bot';
    $token = env('TELEGRAM_BOT_TOKEN');
    $json = file_get_contents($url.$token.'/getUpdates');
    $data = json_decode($json,true);
    dd($data['result']);
    foreach ($data['result'] as $message)
    {
        $offset = $message['update_id'] + 1;
        $chatId = $message['message']['chat']['id'];
        $text = $message['message']['text'];
    }
    //https://api.telegram.org/bot123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11/getMe
    /*
        "id" => 535305796
        "is_bot" => false
        "first_name" => "Oksana"
        "last_name" => "Krylova"
        "language_code" => "ru"
     */
});
