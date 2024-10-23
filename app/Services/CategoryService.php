<?php

namespace App\Services;

use App\Repositories\Interfaces\ICategoryRepository;
use App\Services\Interfaces\ICategoryService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CategoryService implements ICategoryService
{
    public function __construct(private ICategoryRepository $categoryRepository) {}


    public function getCategories()
    {
        return $this->categoryRepository->getCategories();
    }

    public function getCategoryById(int $id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        return $this->categoryRepository->getCategoryById($id);
    }

    public function createCategory(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        return $this->categoryRepository->createCategory($data);
    }

    public function updateCategory(int $id, array $data)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function deleteCategory(int $id, string $title)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        if (empty($title)) {
            throw new \InvalidArgumentException('The title parameter is required.');
        }

        $category = $this->categoryRepository->getCategoryById($id);

        if (empty($category)) {
            throw new HttpException(404, "The category with the specified ID was not found.");
        }

        //TODO - Add redis key check here to see the try count of the title check

        if ($category->title !== $title) {
            throw new HttpException(404, "The title does not match the category title. Please try again.");
        }

        return $this->categoryRepository->deleteCategory($id);
    }
}
