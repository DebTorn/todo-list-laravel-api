<?php

namespace App\Services\Interfaces;

interface ICategoryService
{
    public function getCategories();
    public function getCategoryById(int $id);
    public function createCategory(array $data);
    public function updateCategory(int $id, array $data);
    public function deleteCategory(int $id, string $title);
}
