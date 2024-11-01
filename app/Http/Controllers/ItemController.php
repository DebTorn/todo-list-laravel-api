<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteItemRequest;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\Interfaces\IItemService;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ItemController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', except: []),
        ];
    }

    public function __construct(
        private IItemService $itemService
    ) {}

    /**
     * Get all items from a list by id
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function index(int $listId = null, int $itemId = null)
    {

        try {

            if (empty($listId) && empty($itemId)) {
                throw new HttpException(400, "The list ID or list ID and item ID fields are required.");
            }

            $ret = null;
            $message = "";

            if (!empty($listId) && !empty($itemId)) {
                $ret = $this->itemService->getItemById($itemId);

                if (empty($ret)) {
                    throw new HttpException(404, "The item with the specified ID was not found.");
                }
                $message = "The item fetched successfully";
            } else {
                $ret = $this->itemService->getItems($listId);

                if (count($ret) == 0) {
                    throw new HttpException(404, "The items with the specified list ID were not found.");
                }

                $message = "The items fetched successfully";
            }

            return response()->json([
                "message" => $message,
                "items" => $ret
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }

        $items = $this->itemService->getItems($id);

        return response()->json([
            'message' => 'The items fetched successfully',
            'items' => $items
        ], 200);
    }

    /**
     * Create a new item
     *
     * @param StoreItemRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreItemRequest $request)
    {
        try {
            $data = $request->validated();

            $ret = $this->itemService->createItem($data);

            return response()->json([
                "message" => "The item was created successfully",
                "item" => $ret
            ], 201);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }

    /**
     * Update an item
     *
     * @param StoreItemRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(UpdateItemRequest $request, int $id)
    {
        try {
            if (empty($id)) {
                throw new HttpException(400, "The ID field is required.");
            }

            $data = $request->validated();

            $this->itemService->updateItem($id, $data);

            return response()->json([
                "message" => "The item was updated successfully"
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }

    /**
     * Delete an item
     *
     * @return JsonResponse
     */
    public function destroy(DeleteItemRequest $request)
    {
        try {
            $data = $request->validated();
            $ret = null;
            $message = "";

            if (
                isset($data['item_id']) &&
                isset($data['list_id']) &&
                !empty($data['item_id']) &&
                !empty($data['list_id'])
            ) {
                $ret = $this->itemService->deleteItem($data['item_id']);

                if (empty($ret)) {
                    throw new HttpException(404, "The item with the specified ID was not found.");
                }

                $message = "The item was deleted successfully";
            } else {
                $ret = $this->itemService->deleteAllItem($data['list_id']);
                $message = "The items deleted successfully";
            }

            return response()->json([
                "message" => $message
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }
}
