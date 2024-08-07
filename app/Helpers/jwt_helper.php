<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getTokenUserId($header)
{
    $decoded = decodeToken($header);
    $userId = $decoded->sub->id;
    return $userId;
}

function decodeToken($header)
{

    $key = getenv('JWT_SECRET');
    $token = null;

    // extract the token from the header
    if (!empty($header)) {
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }
    }
    $decoded = JWT::decode($token, new Key($key, 'HS256'));

    return $decoded;
}