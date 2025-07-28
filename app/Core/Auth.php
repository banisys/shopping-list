<?php

namespace App\Core;

use App\Models\Token;
use App\Models\User;

class Auth
{
    public static function user()
    {
        $token = null;

        $headers = getallheaders();
        if (isset($headers['Authorization']) && str_starts_with($headers['Authorization'], 'Bearer ')) {
            $token = substr($headers['Authorization'], 7);
        }

        if (!$token && isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
        }

        if (!$token) return null;

        $tokenModel = Token::findValid($token);
        if (!$tokenModel) return null;

        return User::findById($tokenModel->user_id);
    }
}
