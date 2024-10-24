<?php

namespace App\Services;

use App\Repositories\Interfaces\IListRepository;
use App\Services\Interfaces\IListService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ListService implements IListService
{
    public function __construct(private IListRepository $listRepository) {}

    public function createList(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        return $this->listRepository->createList($data);
    }

    public function updateList(int $id, array $data)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        return $this->listRepository->updateList($id, $data);
    }

    public function deleteList(int $id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        $list = $this->listRepository->findList($id);

        if (empty($list)) {
            throw new HttpException(404, "The list with the specified ID was not found.");
        }

        return $this->listRepository->deleteList($id);
    }

    public function getList(int $id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        return $this->listRepository->findList($id);
    }

    public function getLists(int $categoryId = null)
    {
        return $this->listRepository->findAllList($categoryId);
    }
}
