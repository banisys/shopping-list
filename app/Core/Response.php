<?php

namespace App\Core;

class Response
{
    /**
     * Send a JSON response with the specified data and HTTP status code.
     *
     * @param array $data The data to be encoded as JSON.
     * @param int $status The HTTP status code (default: 200).
     * @return void
     */
    public static function json(array $data, $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
