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
            'cookie' => 'laravel_token=eyJpdiI6IkxIemIycjRiclNKNVJmRzhYTUs5NWc9PSIsInZhbHVlIjoieEhqMkZ4b1ZodjVwOHp0WUFBdEt4UXRJYjB6aktzQ1l5dzlSSkZLSTZPZlBLeGhvbzlFUFAvT01IaWxWWC9kMU5lbmJMODdDZGdwM2dqMGh3L3FWTnpseEFvL2pybVRoazlDUzFYWFU4VXJHTXMzSEtJbVZSWnVlSUc0MVNSNzJBQndRMTJ0dmZzTVB3MTZscTMxa1RVVUxZaEx5d2gwSXJVb2RNcFk0YkJUNE85YXdWWWJaOHJNVzBjejc4akpmRUhONTlHdTBhd24rNCtNbzdnYXYxQTJBQkI2SlhGYXJpV0MzMjVKOThxMSs0K01hSG1lZVBzd3hNby9HZWRqbDVKZkJCOXdWcXRCbXhVNks5ZHl4OTVTNkROamc2aWd2S2FoVUV6TlZyU1Fjb1JLa2I0czhLUE43Unc0T1NCdDAiLCJtYWMiOiIyOWZmNTc4YmQ3OTgwMjU1ZjE4ZTA2YmMzZjY3NDVmYzY5MGI4NmUyZWNmMTk3ZjA2Nzk1YjZmOTJiMDQzYTgzIn0',
            'x-xsrf-token' => 'eyJpdiI6InJhVTV6Q1dscldBUXVNVjlKdU5RbHc9PSIsInZhbHVlIjoiUTYyaVF6ZGhFSEhXcGdUSFY2Ulh2V1hRR1RwdVN2ZUhBMzNhdGJHV2h3ZFcvTW40ZGJhZjJya1pkVHUzbVoxS0oyTmkvczVaaDZkZHIxSkpGWThQL0x2VmNYTE5FOUVBZXpkWHFFRnp5VlpuK1V6dVk3c1Eva3BET3RyamhsdDYiLCJtYWMiOiIxYWIwNzk5MWNjY2NlYzMwN2ZiNWUyMjJmYjYzNTNhYzg3NjYwZTJjZmNlNTI2YTcwZDkyZjdkMWEyYzIyZTM1In0'
        ];

        $responseBarchart = $client->get('https://www.barchart.com/proxies/core-api/v1/quotes/get?fields=symbol%2CcontractSymbol%2ClastPrice%2CpriceChange%2CopenPrice%2ChighPrice%2ClowPrice%2CpreviousPrice%2Cvolume%2CopenInterest%2CtradeTime%2CsymbolCode%2CsymbolType%2ChasOptions&list=futures.contractInRoot&root=LQ&meta=field.shortName%2Cfield.type%2Cfield.description&hasOptions=true&page=1&limit=100&raw=1', ['headers' => $options]);

        $data = json_decode($responseBarchart->getBody()->getContents());

        $lastPrice = $data->data[0]->lastPrice;
        $contractSymbol = $data->data[0]->contractSymbol;
        $priceChange = $data->data[0]->priceChange;

        $text = $contractSymbol."\n".
                "Last: ".$lastPrice."\n".
                "Change: ".$priceChange;

        $response = $telegram->sendMessage([
            'chat_id' => '5062635856',
            'text' => $text
          ]);

        return $response->getMessageId();
    }
}
