<?php

namespace App\Core;

class Request
{
    /**
     * Get the request URI without query parameters.
     *
     * @return string The cleaned request URI.
     */
    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $position = strpos($uri, '?');

        return $position === false ? $uri : substr($uri, 0, $position);
    }

    /**
     * Get the HTTP request method.
     *
     * @return string The HTTP request method (e.g., GET, POST).
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get the parsed JSON body of the request.
     *
     * @return array The decoded JSON data from the request body.
     */
    public static function body(): array
    {
        return json_decode(file_get_contents("php://input"), true);
    }
}
