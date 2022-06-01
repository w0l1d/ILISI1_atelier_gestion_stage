<?php

/*
This call sends a message to one recipient.
*/
require __DIR__ . '/../vendor/autoload.php';

use \Mailjet\Resources;

$mj = new \Mailjet\Client('ae9c9c9a472707ab00ffd5bb3e46bd90', '1d19d9ad2a5448837dd6d32196e3b25f', true, ['version' => 'v3.1']);
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "vakediw497@about27.com",
                'Name' => "Mailjet Pilot"
            ],
            'To' => [
                [
                    'Email' => "ahdoufwalid@gmail.com",
                    'Name' => "ahdouf walid"
                ]
            ],
            'Subject' => "Your email flight plan!",
            'TextPart' => "Dear passenger 1, welcome to Mailjet! May the delivery force be with you!",
            'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3><br />May the delivery force be with you!"
        ]
    ]
];
$response = $mj->post(Resources::$Email, ['body' => $body]);
$response->success() && var_dump($response->getData());
echo "hello";
echo getenv('MJ_APIKEY_PUBLIC');