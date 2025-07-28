<?php

namespace App\Core;

use App\Controllers\ItemController;
use App\Controllers\AuthController;

class Router
{
    /**
     * Router constructor.
     *
     * @param Request $request The HTTP request instance.
     */
    public function __construct(
        private Request $request,
    ) {}

    /**
     * Resolve the request by routing it to the appropriate controller method.
     *
     * @return void
     */
    public function resolve(): void
    {
        $method = $this->request->method();
        $uri = trim($this->request->uri(), '/');

        if ($uri === 'api/login' && $method === 'POST') {
            (new AuthController)->login();
        }

        if ($uri === 'api/register' && $method === 'POST') {
            (new AuthController)->register();
        }

        if ($uri === 'api/items' && $method === 'GET') {

            $user = Auth::user();
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            (new ItemController)->index();
        }

        if ($uri === 'api/items' && $method === 'POST') {
            (new ItemController)->store();
        }

        if (preg_match('#^api/items/(\d+)$#', $uri, $matches) && $method === 'PUT') {
            (new ItemController)->update($matches[1]);
        }

        if (preg_match('#^api/items/(\d+)/check$#', $uri, $matches) && $method === 'PATCH') {
            (new ItemController)->check($matches[1]);
        }

        if (preg_match('#^api/items/(\d+)$#', $uri, $matches) && $method === 'DELETE') {
            (new ItemController)->destroy($matches[1]);
        }

        if ($uri === 'login') {
            $user = Auth::user();
            if ($user) {
                header("Location: /items");
                die();
            }
            require __DIR__ . '/../Views/auth/login.php';
            exit;
        }

        if ($uri === 'register') {
            $user = Auth::user();
            if ($user) {
                header("Location: /items");
                die();
            }
            require __DIR__ . '/../Views/auth/register.php';
            exit;
        }

        if ($uri === '' || $uri === 'items') {
            $user = Auth::user();
            if (!$user) {
                header("Location: /login");
                die();
            }

            require __DIR__ . '/../Views/items/index.php';
            exit;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
