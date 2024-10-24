<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{
    public function getCategories()
    {
        return Category::all();
    }

    public function getCategoryById(int $id)
    {
        return Category::find($id);
    }

    public function getCategoryByTitle(string $title, int $id = null)
    {
        $category = Category::where('title', $title);

        if (!empty($id)) {
            $category->where('id', $id);
        }

        return $category->first();
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data)
    {
        return Category::find($id)->update($data);
    }

    public function deleteCategory(int $id)
    {
        return Category::destroy($id);
    }
}
