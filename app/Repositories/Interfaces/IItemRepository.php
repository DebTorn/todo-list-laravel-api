<?php

namespace App\Repositories\Interfaces;

use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;

interface IItemRepository
{
    public function createItem(array $data): Item;
    public function updateItem(int $id, array $data): ?int;
    public function deleteItem(int $id): bool;
    public function deleteAllItem(int $listId): bool;
    public function findItem(int $id): ?Item;
    public function findAllItem(int $listId): Collection;
}
