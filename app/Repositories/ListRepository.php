<?php

namespace App\Repositories;

use App\Models\TodoList;
use App\Repositories\Interfaces\IListRepository;
use Illuminate\Support\Facades\Auth;

class ListRepository implements IListRepository
{
    public function createList(array $data)
    {
        $insertedList = TodoList::create($data);

        return TodoList::where('id', $insertedList->id)->with(['category'])->first()->makeHidden('category_id');
    }

    public function updateList(int $id, array $data)
    {
        $list = TodoList::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$list) {
            return null;
        }

        $list->update($data);
        return $list;
    }

    public function deleteList(int $id)
    {

        $list = TodoList::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$list) {
            return null;
        }

        return TodoList::destroy($id);
    }

    public function findList(int $id)
    {
        return TodoList::where('id', $id)->where('user_id', Auth::id())->first();
    }

    public function findAllList(int $categoryId = null)
    {
        $lists = TodoList::where('user_id', Auth::id());

        if (!is_null($categoryId)) {
            $lists->where('category_id', $categoryId);
        }

        $result = $lists->with(['category'])->get()->makeHidden('category_id');

        return $result;
    }
}
