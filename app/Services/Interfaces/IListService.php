<?php

namespace App\Services\Interfaces;

interface IListService
{
    public function createList(array $data);
    public function updateList(int $id, array $data);
    public function deleteList(int $id);
    public function getList(int $id);
    public function getLists(int $categoryId = null);
}
