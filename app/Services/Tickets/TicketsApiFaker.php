<?php

namespace App\Services\Tickets;

use Illuminate\Http\Request;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use League\ISO3166\ISO3166;
use App\Services\SettingService;

class TicketsApiFaker
{

    const FAKE_API_RESPONSE = '{"messages":[{"ticket_id":"4745","from":{"id":"1062880978","user_name":"Motorolaxts5000","first_name":"Александр","last_name":"Савельев"},"message_id":"851706","text":"Жалоба здравствуйте сколько можно вам писать о сломаным ВПН.","reply_message_id":"0","is_response":false,"media":null,"state":"support","created_at":"2022-07-28T06:13:29Z","created_at_unix":"1658988809"},{"ticket_id":"4746","from":{"id":"1062880978","user_name":"Motorolaxts5000","first_name":"Александр","last_name":"Савельев"},"message_id":"851713","text":"","reply_message_id":"0","is_response":false,"media":{"filename":"image.jpg","content_type":"image/jpg","thumb":{"file_id":"AgACAgIAAxkBAAEM_wFi4ikVzxGhUbCRNOx5P2lWTUtz7wACKsgxG22VEUtWOIwmDepGwQEAAwIAA20AAykE","file_size":"21235","width":"240","height":"320","link":"https://api.telegram.org/file/bot5504900483:AAFws71Igs7SzZVziEpxCkRicqmp_f3XUFo/photos/file_97.jpg","error":""},"file":{"file_id":"AgACAgIAAxkBAAEM_wFi4ikVzxGhUbCRNOx5P2lWTUtz7wACKsgxG22VEUtWOIwmDepGwQEAAwIAA3kAAykE","file_size":"179032","width":"960","height":"1280","link":"https://api.telegram.org/file/bot5504900483:AAFws71Igs7SzZVziEpxCkRicqmp_f3XUFo/photos/file_98.jpg","error":""}},"state":"main_menu_selector","created_at":"2022-07-28T06:13:41Z","created_at_unix":"1658988821"},{"ticket_id":"4747","from":{"id":"0","user_name":"","first_name":"","last_name":""},"message_id":"851760","text":"Здравствуйте. Это приложение давно не поддерживается и не работает, воспользуйтесь ботом для получения инструкции и профиля openvpn","reply_message_id":"851713","is_response":true,"media":null,"state":"","created_at":"2022-07-28T06:16:57Z","created_at_unix":"1658989017"}]}';

    public function __construct()
    {

    }

    public static function getFakeApiResponse()
    {
        return json_decode(self::FAKE_API_RESPONSE);
    }

}


