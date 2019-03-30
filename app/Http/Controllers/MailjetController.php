<?php

namespace App\Http\Controllers;

use Mailjet\Client;
use \Mailjet\Resources;
use Illuminate\Http\Request;

class MailjetController extends Controller
{
    public static function sendEmail($opts)
    {
        $mj = new Client(env('MJ_APIKEY_PUBLIC'), env('MJ_APIKEY_PRIVATE'), true,['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'info@grandprixacdelco.com',
                        'Name' => 'Grand Prix ACDelco'
                    ],
                    'To' => [
                        [
                            'Email' => $opts['user_email'],
                            'Name' => $opts['user_name']
                        ]
                    ],
                    'Subject' => $opts['email_subject'],
                    'TextPart' => $opts['email_description'],
                    'HTMLPart' => $opts['email_template']
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

        if ($response->success()) {
            return true;
        }

        return false;
    }
}
