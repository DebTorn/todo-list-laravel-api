<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteListRequest;
use App\Http\Requests\StoreListRequest;
use App\Services\Interfaces\IListService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ListController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', except: []),
        ];
    }

    public function __construct(
        private IListService $listService
    ) {}

    public function index(Request $request, int $id = null)
    {
        try {
            $ret = null;
            $categoryId = null;
            $message = "";

            if (!empty($request->query('category_id'))) {
                $categoryId = $request->query('category_id');
            }

            if (!empty($id)) {
                $ret = $this->listService->getList($id);

                if (empty($ret)) {
                    throw new HttpException(404, "The list with the specified ID was not found.");
                }
                $message = "The list fetched successfully";
            } else {
                $ret = $this->listService->getLists($categoryId);
                $message = "The lists fetched successfully";
            }

            return response()->json([
                "message" => $message,
                "lists" => $ret
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

    public function store(StoreListRequest $request)
    {
        try {
            $data = $request->validated();

            $data['user_id'] = Auth::id();

            $ret = $this->listService->createList($data);

            return response()->json([
                "message" => "The list was created successfully",
                "inserted_list" => $ret
            ], 201);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }

    public function destroy(int $id)
    {
        try {

            if (empty($id)) {
                throw new HttpException(400, "The ID field is required.");
            }

            $ret = $this->listService->deleteList($id);

            return response()->json([
                "message" => "The list deleted successfully",
                "deleted_list" => $ret
            ], 200);
        } catch (\InvalidArgumentException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "The processing of the arguments was unsuccessful."], 500);
        } catch (HttpException $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['message' => "An error occurred while processing the request."], 500);
        }
    }
}
