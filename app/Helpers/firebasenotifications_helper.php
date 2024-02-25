<?php

function sendNotification($title, $body, $tokens)
{
    $headers = [
        'Authorization: key=AAAATCEeJYU:APA91bGgtBQNg3kUAriQOvLjkV44ZsYiIFF1pQSzSraMQ62s5DX2CxzEZ6MJj7R5V1dtU2_FG4M-xu-R2lO0Mxi9pQYEqzdvmnfyhqbQhcksnEro8TvAbJ7rNg9-z06fatyeszuQKNZd',
        'Content-Type: application/json'
    ];

    $request = [
        'data' => [
            'title' => $title,
            'body' => $body,
        ],
        'registration_ids' => $tokens,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

    $res = curl_exec($ch);

    curl_close($ch);

    return $res;
}