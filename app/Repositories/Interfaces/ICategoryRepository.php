<?php

namespace App\Repositories\Interfaces;

interface ICategoryRepository
{
    public function getCategories();
    public function getCategoryById(int $id);
    public function getCategoryByTitle(string $title, int $id = null);
    public function createCategory(array $data);
    public function updateCategory(int $id, array $data);
    public function deleteCategory(int $id);
}
