<?php

namespace App\Services;

use App\Services\Interfaces\IItemService as InterfaceItemService;
use App\Repositories\Interfaces\IItemRepository as InterfaceItemRepository;
use App\Repositories\Interfaces\IListRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ItemService implements InterfaceItemService
{
    public function __construct(private InterfaceItemRepository $itemRepository, private IListRepository $iListRepository) {}

    /**
     * Get all items from a list by id
     *
     * @param int $listId
     *
     * @return Item
     */
    public function getItems(int $listId)
    {
        if (empty($listId)) {
            throw new \InvalidArgumentException('The list ID parameter is required.');
        }

        return $this->itemRepository->findAllItem($listId);
    }

    /**
     * Get an item by id
     *
     * @param int $id
     *
     * @return Item
     */
    public function getItemById(int $id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        return $this->itemRepository->findItem($id);
    }

    /**
     * Create a new item
     *
     * @param array $data
     *
     * @return Item
     */
    public function createItem(array $data)
    {
        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        if (!isset($data['list_id']) || (isset($data['list_id']) && empty($data['list_id']))) {
            throw new HttpException(422, 'The list_id parameter is required.');
        }

        $list = $this->iListRepository->findList($data['list_id']);

        if (!$list) {
            throw new HttpException(404, 'The list does not exist.');
        }

        return $this->itemRepository->createItem($data);
    }

    /**
     * Update an item
     *
     * @param int $id
     *
     * @param array $data
     */
    public function updateItem(int $id, array $data)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        if (empty($data)) {
            throw new \InvalidArgumentException('The data parameter is required.');
        }

        if (!isset($data['list_id']) || (isset($data['list_id']) && empty($data['list_id']))) {
            throw new HttpException(422, 'The list_id parameter is required.');
        }

        $list = $this->iListRepository->findList($data['list_id']);

        if (!$list) {
            throw new HttpException(404, 'The list does not exist.');
        }

        if ($this->itemRepository->updateItem($id, $data) == 0) {
            throw new HttpException(500, "The update was unsuccessful.");
        }

        return true;
    }

    /**
     * Delete an item
     *
     * @param int $id
     *
     * @return bool
     */
    public function deleteItem(int $id)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('The id parameter is required.');
        }

        return $this->itemRepository->deleteItem($id);
    }

    /**
     * Delete all items from a list by id
     *
     * @param int $listId
     *
     * @return bool
     */
    public function deleteAllItem(int $listId)
    {
        if (empty($listId)) {
            throw new \InvalidArgumentException('The list ID parameter is required.');
        }

        return $this->itemRepository->deleteAllItem($listId);
    }
}
