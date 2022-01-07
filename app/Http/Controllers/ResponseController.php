<?php

namespace App\Http\Controllers;

use Telegram\Bot\Api;
use GuzzleHttp\Client;

class ResponseController extends Controller
{
    public function __construct()
    {
        //
    }

    public function price()
    {
        $client = new Client();
        $bot_token = config('app.telegram_bot_token');
        $telegram = new Api($bot_token);
        $options = [
            'cookie' => 'laravel_token='.config('app.barchart_laravel_token'),
            'x-xsrf-token' => config('app.barchart_xsrf_token')
        ];

        $responseBarchart = $client->get('https://www.barchart.com/proxies/core-api/v1/quotes/get?fields=symbol%2CcontractSymbol%2ClastPrice%2CpriceChange%2CopenPrice%2ChighPrice%2ClowPrice%2CpreviousPrice%2Cvolume%2CopenInterest%2CtradeTime%2CsymbolCode%2CsymbolType%2ChasOptions&list=futures.contractInRoot&root=LQ&meta=field.shortName%2Cfield.type%2Cfield.description&hasOptions=true&page=1&limit=100&raw=1', ['headers' => $options]);

        $data = json_decode($responseBarchart->getBody()->getContents());

        $lastPrice = $data->data[0]->lastPrice;
        $contractSymbol = $data->data[0]->contractSymbol;
        $priceChange = $data->data[0]->priceChange;

        $text = $contractSymbol."\n".
                "Last: ".$lastPrice."\n".
                "Change: ".$priceChange;

        $chatId = $telegram->getUpdates()[0]['message']['chat']['id'];

        $response = $telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text
          ]);

        return $response->getMessageId();
    }
}
