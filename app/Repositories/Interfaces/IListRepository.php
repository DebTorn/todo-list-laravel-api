<?php

namespace App\Repositories\Interfaces;

interface IListRepository
{
    public function createList(array $data);
    public function updateList(int $id, array $data);
    public function deleteList(int $id);
    public function findList(int $id);
    public function findAllList(int $categoryId = null);
}
