<?php

namespace App\Services;

use App\Repositories\Interfaces\ICategoryRepository;
use App\Services\Interfaces\ICategoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
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

        if ($category->title !== $title) {

            $redisKey = intval(Redis::get('category:delete:try:count:' . Auth::id()));

            if (!$redisKey) {
                $cooldownKey = intval(Redis::get('category:delete:try:cooldown:' . Auth::id()));
                if ($cooldownKey) {
                    if ($cooldownKey > time()) {
                        throw new HttpException(404, "The title does not match the category title. You need to wait for " . ($cooldownKey - time()) . " sec.");
                    }

                    Redis::del('category:delete:try:cooldown:' . Auth::id());
                }

                Redis::set('category:delete:try:count:' . Auth::id(), config('app.title_check_max_try'));

                throw new HttpException(404, "The title does not match the category title. Please try again. (" . config('app.title_check_max_try') . " try left)");
            } else {

                $currentKeyValue = $redisKey - 1;

                if ($redisKey <= 0 || $currentKeyValue === 0) {
                    Redis::del('category:delete:try:count:' . Auth::id());
                    Redis::set('category:delete:try:cooldown:' . Auth::id(), time() + intval(config('app.title_check_cooldown')));

                    throw new HttpException(404, "The title does not match the category title. You need to wait for " . config('app.title_check_cooldown') . " sec.");
                }

                Redis::set('category:delete:try:count:' . Auth::id(), $currentKeyValue);

                throw new HttpException(404, "The title does not match the category title. Please try again. (" . $currentKeyValue . " try left)");
            }
        }

        return $this->categoryRepository->deleteCategory($id);
    }
}
