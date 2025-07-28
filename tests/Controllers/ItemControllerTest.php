<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ItemController;

class ItemControllerTest extends TestCase
{
    public function testIndexReturnsItemsJson()
    {
        ob_start();
        $controller = new ItemController();
        $controller->index();
        $output = ob_get_clean();

        $data = json_decode($output, true);

        $this->assertIsArray($data);

        if (!empty($data)) {
            $this->assertArrayHasKey('id', $data[0]);
            $this->assertArrayHasKey('name', $data[0]);
            $this->assertArrayHasKey('is_checked', $data[0]);
        }
    }
}
