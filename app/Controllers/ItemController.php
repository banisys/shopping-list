<?php

namespace App\Controllers;

use App\Models\Item;
use App\Core\Request;
use App\Core\Response;

class ItemController
{
    /**
     * Display a listing of all items.
     *
     * @return void
     */
    public function index(): void
    {
        $items = Item::all();

        Response::json($items);
    }

    /**
     * Store a newly created item in storage.
     *
     * @return void
     */
    public function store(): void
    {
        $data = Request::body();
        if (!isset($data['name']) || empty(trim($data['name']))) {
            Response::json(['error' => 'Item name is required'], 422);
            return;
        }

        $item = new Item();
        $item->name = $data['name'];
        $item->save();

        Response::json($item, 201);
    }

    /**
     * Update the specified item in storage.
     *
     * @param int $id The ID of the item to update
     * @return void
     */
    public function update(int $id): void
    {
        $data = Request::body();
        $item = Item::find($id);

        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
            return;
        }

        if (isset($data['name'])) {
            $item->name = $data['name'];
        }

        $item->save();
        Response::json($item);
    }

    /**
     * Toggle the checked status of the specified item.
     *
     * @param int $id The ID of the item to check/uncheck
     * @return void
     */
    public function check(int $id): void
    {
        $item = Item::find($id);

        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
            return;
        }

        $item->is_checked = !$item->is_checked;
        $item->save();

        Response::json($item);
    }

    /**
     * Remove the specified item from storage.
     *
     * @param int $id The ID of the item to delete
     * @return void
     */
    public function destroy(int $id): void
    {
        $item = Item::find($id);

        if (!$item) {
            Response::json(['error' => 'Item not found'], 404);
            return;
        }

        $item->delete();
        Response::json(['message' => 'Item deleted']);
    }
}
