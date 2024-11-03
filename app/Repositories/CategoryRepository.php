<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{

    /**
     * Get all categories
     *
     * @return Category
     */
    public function getCategories()
    {
        return Category::all();
    }

    /**
     * Get category by id
     *
     * @param int $id
     *
     * @return Category
     */
    public function getCategoryById(int $id)
    {
        return Category::find($id);
    }

    /**
     * Get category by title
     *
     * @param string $title
     * @param int $id
     *
     * @return Category
     */
    public function getCategoryByTitle(string $title, int $id = null)
    {
        $category = Category::where('title', $title);

        if (!empty($id)) {
            $category->where('id', $id);
        }

        return $category->first();
    }

    /**
     * Create a new category
     *
     * @param array $data
     *
     * @return Category
     */
    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    /**
     * Update a category
     *
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function updateCategory(int $id, array $data)
    {
        return Category::find($id)->update($data);
    }

    /**
     * Delete a category
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteCategory(int $id)
    {
        return Category::destroy($id);
    }
}
