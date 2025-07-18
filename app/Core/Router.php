<?php

namespace App\Core;

use App\Controllers\ItemController;

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

        if ($uri === 'api/items' && $method === 'GET') {
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

        if ($uri === '' || $uri === 'items') {
            require __DIR__ . '/../Views/items/index.php';
            exit;
        }

        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    }
}
