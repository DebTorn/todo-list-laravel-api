<?php

namespace App\Repositories;

use App\Models\Item;
use App\Repositories\Interfaces\IItemRepository as InterfaceItemRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ItemRepository implements InterfaceItemRepository
{
    /**
     * Create a new item
     *
     * @param array $data
     *
     * @return Item
     */
    public function createItem(array $data): Item
    {
        return Item::create($data);
    }

    /**
     * Update an item
     *
     * @param int $id
     *
     * @param array $data
     *
     * @return int|null
     */
    public function updateItem(int $id, array $data): ?int
    {
        return Item::whereHas('list', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete an item
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteItem(int $id): bool
    {
        return Item::whereHas('list', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->where('id', $id)
            ->delete();
    }

    /**
     * Find an item
     *
     * @param int $id
     *
     * @return Item|null
     */
    public function findItem(int $id): ?Item
    {
        return Item::whereHas('list', function ($query) {
            $query->where('user_id', Auth::id());
            $query->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            });
        })
            ->where('id', $id)
            ->first();
    }

    /**
     * Find all items
     *
     * @param int $listId
     *
     * @return Collection
     */
    public function findAllItem(int $listId): Collection
    {
        return Item::whereHas('list', function ($query) use ($listId) {
            $query->where('id', $listId);
            $query->where('user_id', Auth::id());
            $query->whereHas('category', function ($query) {
                $query->whereNull('deleted_at');
            });
        })->get();
    }

    /**
     * Delete all items from a list by id
     *
     * @param int $listId
     *
     * @return bool
     */
    public function deleteAllItem(int $listId): bool
    {
        return Item::whereHas('list', function ($query) use ($listId) {
            $query->where('id', $listId);
            $query->where('user_id', Auth::id());
        })->delete();
    }
}
