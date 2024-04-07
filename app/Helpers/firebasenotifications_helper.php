<?php

function sendNotification($title, $body, $tokens)
{
    $serverKey = getenv('FIREBASE_SERVER_KEY');;
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = [
        'Authorization: key=' . $serverKey,
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
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

    $res = curl_exec($ch);

    if ($res === false) {
        // Handle curl error
        $error = curl_error($ch);
        // You might want to log this error for debugging
        // echo "Curl error: " . $error;
        return $error;
    } else {
        // Check for HTTP status code (if needed)
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // You might want to handle different status codes differently
        // echo "HTTP status code: " . $statusCode;
    }

    curl_close($ch);

    return ['result' => $res, 'statusCode' => $statusCode];
}