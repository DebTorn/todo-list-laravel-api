<?php

namespace App\Services\Interfaces;

interface IItemService
{
    public function getItems(int $listId);
    public function getItemById(int $id);
    public function createItem(array $data);
    public function updateItem(int $id, array $data);
    public function deleteItem(int $id);
    public function deleteAllItem(int $listId);
}
