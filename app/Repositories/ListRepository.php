<?php

namespace App\Repositories;

use App\Models\TodoList;
use App\Repositories\Interfaces\IListRepository;
use Illuminate\Support\Facades\Auth;

class ListRepository implements IListRepository
{

    /**
     * Create a new list
     *
     * @param array $data
     *
     * @return TodoList
     */
    public function createList(array $data)
    {
        $insertedList = TodoList::create($data);

        return TodoList::where('id', $insertedList->id)->with(['category'])->first()->makeHidden('category_id');
    }

    /**
     * Update a list
     *
     * @param int $id
     *
     * @param array $data
     */
    public function updateList(int $id, array $data)
    {
        $list = TodoList::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$list) {
            return null;
        }

        $list->update($data);
        return $list;
    }

    /**
     * Delete a list
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteList(int $id)
    {

        $list = TodoList::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$list) {
            return null;
        }

        return TodoList::destroy($id);
    }

    /**
     * Find a list
     *
     * @param int $id
     *
     * @return TodoList
     */
    public function findList(int $id)
    {
        return TodoList::whereHas('category', function ($query) {
            $query->whereNull('deleted_at');
        })
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
    }

    /**
     * Find all lists
     *
     * @param int $categoryId
     *
     * @return TodoList
     */
    public function findAllList(int $categoryId = null)
    {
        $lists = TodoList::where('user_id', Auth::id());

        if (!is_null($categoryId)) {
            $lists->where('category_id', $categoryId);
        }

        $result = $lists->whereHas('category', function ($query) {
            $query->whereNull('deleted_at');
        })
            ->with(['category'])
            ->get()
            ->makeHidden('category_id');

        return $result;
    }
}
