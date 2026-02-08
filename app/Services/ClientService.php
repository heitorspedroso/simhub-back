<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Client;

class ClientService
{
    public static function getAll($user, $attr = null, $order = null)
    {
        if ($user->USR_MASTER === 'S')
            $client = new Client; // $client = Client::all();
        else if ($user->USR_MASTER === 'M')
            $client = $user->client();
        else
            return [];

        if ($attr)
            $client->select($attr);
        if ($order)
            $client->orderBy($order);

        $client = $client->with(['device' => function ($q) {
            $q->select(['EQP_ID', 'EQP_CLI_ID'])->with([
                'alertOne' => function ($q) {
                    $q->select('ALT_EQP_ID');
                }
            ]);
        }])->get();

        if (!$client)
            return [];

        foreach ($client->toArray() as &$c) {
            foreach ($c['device'] as $d) {
                if ($d['alert_one']) {
                    $c['CLI_ATIVO'] = 'N';
                    break;
                }
            }

            unset($c['device']);
        }

        return $client;
    }
}


// foreach ($panel['alert'] as $a) {

//     foreach ($panel['device'] as &$d) {
//         if ($a['EQP_ID'] == $d['EQP_ID']) {
//             $cliAlert[$d['client']['CLI_ID']] = true;
//         }
//     }
// }

// foreach ($panel['client'] as &$c) {

//     if (isset($cliAlert[$c['CLI_ID']]))
//         $c['CLI_ATIVO'] = 'N';
// }
